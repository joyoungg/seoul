<?php

namespace App\Http\Controllers;

use App\Agent;
use App\House;
use App\HouseImg;
use App\HouseLog;
use App\NaverStatus;
use App\SafeHouse;
use App\SeoulHouse;
use App\SeoulHouseImg;
use App\User;
use App\WithoutFee;
use Carbon\Carbon;
use DB;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getList(Request $request)
    {
        // 자동으로 현시점에서 일주일치를 생성함
        $start = $request->get('start');
        $end = $request->get('end');

        $housesBuilder = $this->getHouseListByDate([$start, $end]);
        $houses = $housesBuilder->paginate(15);

        return view('list', [
            'houses' => $houses,
        ]);
    }

    public function form()
    {
        return view('form');
    }

    public function create(Request $request)
    {
//        $start = $request->get('start_date');
//        $end = $request->get('end_date');
        $start = Carbon::now()->subDay()->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        // 일주일전 매물 리스트 가져오기
        $housesBuilder = $this->getHouseListByDate([$start, $end]);

        // 한달에 한번씩 NOT LIVE 처리하기

        $cnt = 0;
        $result = [];
        $houseType = [
            'agent' => 'Zero부동산',
            'user'  => '직거래',
        ];

        DB::beginTransaction();
//        $this->setClosed();       // 라이브 끄는 기능은 한달에 한번만 시행!!
        foreach ($housesBuilder->cursor() as $house) {
            try {
                $seoul = SeoulHouse::find($house->hidx);
                if ($seoul) {
                    continue;
                }

                $house->move_date = null;
                $house->ctrtStart = null;
                $house->ctrtEnd = null;
                $house->idx_naver = $this->getIdxNaver($house);
                $house->is_zero = $this->isZero($house);
                $house->is_safe = $this->isSave($house);
                if ($house->userType === 'gosin') {
                    continue;
                }
                // 중개사가 아닌데 제로부동산(그럴리가 없음) 매물 업로드 안함
                if ($house->iz_zero == 1 && $house->userType != 'agent') {
                    continue;
                }
                // 중개인 중에 Zero 회원이 아니면 매물 업로드 안함
                if ($house->is_zero === 0 && $house->userType === 'agent') {
                    continue;
                }
                if (empty($house->userType)) {
                    $userType = 'user';
                } else {
                    $userType = $house->userType->user_type;
                }

                $house->type_seoul = $houseType[$userType];
                $house->user = $this->getUser($house);
                $result[$cnt] = $this->createHouse($house);

                HouseLog::create([
                    'result' => 'insert success',
                    'data'   => json_encode($result[$cnt]),
                ]);
                $cnt++;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                HouseLog::create([
                    'result' => 'insert fail',
                    'data'   => json_encode($e->getMessage()),
                ]);
                return $e->getMessage();
            }
        }

        return "{$cnt}개 성공";
    }

    public function upload()
    {

    }

    public function getHouseListByDate(array $dates)
    {
        // 현재 시점에서 일주일 전것을 가져옴
//        $start = Carbon::now()->subDay()->format('Y-m-d');
//        $end = Carbon::now()->format('Y-m-d');
//        $dates[0] = $start;
//        $dates[1] = $end;

        $housesBuilder = House::withLive()
            ->latest('c_date')
            ->where('hidx', '>', 1000000)
            ->whereBetween('c_date', [$dates[0], $dates[1]]);

        return $housesBuilder;
    }

    private function getIdxNaver($house)
    {
        $flag = null;
        if (in_array($house->status_code, ['0101', '0102', '0103'])
            && NaverStatus::find($house->hidx)) {
            $flag = 1;
        }

        return $flag;
    }

    private function isZero($house)
    {
        $flag = 0;
        if (WithoutFee::find($house->hidx)) {
            $flag = 1;
        }

        return $flag;
    }

    private function isSave($house)
    {
        $flag = 0;
        if (SafeHouse::whereNull('disapproval_at')->where('hidx', $house->hidx)->first()) {
            $flag = 1;
        }

        return $flag;
    }

    private function getUser($house)
    {
        $user = User::find($house->uidx);
        if (empty($user)) {
            return null;
        }
        $type = [
            'user'  => '개인',
            'agent' => '중개',
            'gosin' => '중개',
        ];

        $result = [
            'info'           => [],
            'type'           => $type[$user->user_type],
            'mapping_number' => $user->safe_number,
        ];
        $info = [];
        if ($user->user_type == 'user') {
            $info = [
                '아이디' => $user->email,
            ];
        } elseif ($user->user_type = 'agent') {
            // {\"info\": {\"대표자\": \"유광연\", \"중개사\": \"피터팬의 좋은방 구하기\", \"대표번호\": \"02-2088-5036\"}, \"type\": \"중개\", \"mapping_number\": \"0505714667264\"}
            if (isset($user->agency)) {
                $info = [
                    '대표자'  => $user->agency->ceo_name,
                    '중개사'  => $user->agency->agency_name,
                    '대표번호' => $user->agency->telephone,
                ];
            } else {
                $info = [
                    '대표자'  => '',
                    '중개사'  => '',
                    '대표번호' => '',
                ];
            }

        }
        $result['info'] = $info;

        return json_encode($result);
    }

    public function delete()
    {
//        $db = DB::delete(DB::raw('DELETE FROM TB_HOUSE_SALE WHERE hidx > 0 AND c_date > \'2017-07-25\''));
//        $db = DB::delete(DB::raw('DELETE FROM TB_HOUSE_IMG WHERE hidx > 0 AND img_idx > 0'));
//        dump($db);
        DB::beginTransaction();
        try {
            $seoulHouse = SeoulHouse::select('c_date')
                ->where('c_date', '<=', Carbon::now()->subMonth())
                ->where('hidx', '>', 1000000)
                ->update(['status' => 'NOT_LIVE']);
            HouseLog::create([
                'result' => 'delete success',
                'data'   => json_encode($seoulHouse),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            HouseLog::create([
                'result' => 'delete fail',
                'data'   => json_encode($e->getMessage()),
            ]);
            return $e->getMessage();
        }

        return "삭제 성공";
    }

    private function createHouse($house)
    {
        $result = SeoulHouse::create([
            'hidx'                    => $house->hidx,
            'uidx'                    => $house->uidx,
            'sale_num'                => $house->sale_num,
            'subject'                 => $house->subject,
            'memo'                    => $house->memo,
            'description'             => $house->description,
            'zone_code'               => $house->zone_code,
            'address_type'            => $house->address_type ?: 'r',
            'address'                 => $house->address,
            'road_address'            => $house->road_address,
            'jibun_address'           => $house->jibun_address,
            'building_code'           => $house->building_code,
            'building_name'           => $house->building_name,
            'apartment'               => $house->apartment,
            'sido'                    => $house->sido,
            'sigungu'                 => $house->sigungu,
            'dong'                    => $house->dong,
            'admin_dong'              => $house->admin_dong,
            'sigungu_code'            => $house->sigungu_code,
            'postcode'                => $house->postcode,
            'postcode1'               => $house->postcode1,
            'postcode2'               => $house->postcode2,
            'contract_type'           => $house->contract_type,
            'shorterm_contract'       => $house->shorterm_contract,
            'building_type'           => $house->building_type,
            'room_type'               => $house->room_type,
            'floors'                  => $house->floors,
            'floor'                   => $house->floor,
            'is_octop'                => $house->is_octop,
            'is_half_underground'     => $house->is_half_underground,
            'is_multilayer'           => $house->is_multilayer,
            'supplied_size'           => $house->supplied_size,
            'real_size'               => $house->real_size,
            'build_year'              => $house->build_year,
            'have_loan'               => $house->have_loan,
            'auth_register'           => $house->auth_register,
            'maintenance_cost'        => $house->maintenance_cost,
            'maintenance_included'    => $house->maintenance_included,
            'have_parking_lot'        => $house->have_parking_lot,
            'allow_pet'               => $house->allow_pet,
            'is_full_option'          => $house->is_full_option,
            'have_elevator'           => $house->have_elevator,
            'support_loan'            => $house->support_loan,
            'move_type'               => $house->move_type,
            'move_date'               => $house->move_date,
            'req_agent_contact'       => $house->req_agent_contact,
            'open_contact'            => $house->open_contact,
            'contact_notice'          => $house->contact_notice,
            'status'                  => $house->status,
            'latitude'                => $house->latitude,
            'longitude'               => $house->longitude,
            'favorite'                => $house->favorite,
            'view_count'              => $house->view_count,
            'cash_back_rate'          => $house->cash_back_rate,
            'commission_rate'         => $house->commission_rate,
            'deposit'                 => $house->deposit,
            'monthly_fee'             => $house->monthly_fee,
            'deposit_memo'            => $house->deposit_memo,
            'contact_phone_num'       => $house->contact_phone_num,
            'pp_house_type'           => $house->pp_house_type,
            'pp_size'                 => $house->pp_size,
            'pp_price'                => $house->pp_price,
            'pp_heating_type'         => $house->pp_heating_type,
            'pp_location'             => $house->pp_location,
            'pp_floor'                => $house->pp_floor,
            'pp_options'              => $house->pp_options,
            'pp_parking_lot'          => $house->pp_parking_lot,
            'pp_move_date'            => $house->pp_move_date,
            'pp_contact'              => $house->pp_contact,
            'pp_maintenance'          => $house->pp_maintenance,
            'pp_maintenance_included' => $house->pp_maintenance_included,
            'pp_room_count'           => $house->pp_room_count,
            'pp_details'              => $house->pp_details,
            'pp_op_validation'        => $house->pp_op_validation,
            'pp_last_check_date'      => $house->pp_last_check_date,
            'pp_idx'                  => $house->pp_idx,
            'pp_user_id'              => $house->pp_user_id,
            'pp_source_url'           => $house->pp_source_url,
            'pp_menu'                 => $house->pp_menu,
            'pp_menu_index'           => $house->pp_menu_index,
            'product_idx'             => $house->product_idx,
            'purchase_idx'            => $house->purchase_idx,
            'live_start_date'         => $house->live_start_date,
            'live_end_date'           => $house->live_end_date,
            'c_device'                => $house->c_device,
            'c_date'                  => $house->c_date,
            'm_date'                  => $house->m_date,
            'is_deleted'              => $house->is_deleted,
            'timedir_tmp'             => $house->timedir_tmp,
            'isSafety'                => $house->isSafety,
            'pp_remove'               => $house->pp_remove,
            'process_step_code'       => $house->process_step_code,
            'building_form'           => $house->building_form,
            'is_main_road'            => $house->is_main_road,
            'address2'                => $house->address2,
            'address3'                => $house->address3,
            'status_code'             => $house->status_code,
            'now_object'              => $house->now_object,
            'rec_object'              => $house->rec_object,
            'aptNo'                   => $house->aptNo,
            'ptpNo'                   => $house->ptpNo,
            'bedroom_count'           => $house->bedroom_count,
            'bathroom_count'          => $house->bathroom_count,
            'direction'               => $house->direction,
            'livingroom_form'         => $house->livingroom_form,
            'door_type'               => $house->door_type,
            'is_new_building'         => $house->is_new_building,
            'move_cond'               => $house->move_cond,
            'is_debt'                 => $house->is_debt,
            'debt_amount'             => $house->debt_amount,
            'parking_space'           => $house->parking_space,
            'charter_price'           => $house->charter_price,
            'sale_price'              => $house->sale_price,
            'is_parcel'               => $house->is_parcel,
            'parcel_price'            => $house->parcel_price,
            'parcel_type'             => $house->parcel_type,
            'premium_price'           => $house->premium_price,
            'real_price'              => $house->real_price,
            'ctrtStart'               => $house->ctrtStart,
            'ctrtEnd'                 => $house->ctrtEnd,
            'cortarNo'                => $house->cortarNo,
            'aptDong'                 => $house->aptDong,
            'aptHo'                   => $house->aptHo,
            'deleted_at'              => $house->deleted_at,
            'completion_date'         => $house->completion_date,
            'idx_naver'               => $house->idx_naver,
            'type_seoul'              => $house->type_seoul,
            'is_safe'                 => $house->is_safe,
            'is_zero'                 => $house->is_zero,
            'user'                    => $house->user,
        ]);

        $imgs = HouseImg::where('hidx', $house->hidx)->get();

        foreach ($imgs as $img) {
            SeoulHouseImg::create($img->toArray());
        }

        return $result;
    }

    public
    function getAllHouseList()
    {
        $start = Carbon::now()->subWeek()->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $housesBuilder = $this->getHouseListByDate([$start, $end]);

        return $housesBuilder->get();
    }

    private
    function setClosed()
    {
        SeoulHouse::where('hidx', '>', 0)
            ->update(['status' => 'NOT_LIVE']);
    }
}
