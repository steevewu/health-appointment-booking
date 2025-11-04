<?php


return [



    'email' => 'Địa chỉ email',
    'navigation_label' => 'Quản Lý :model',
    'full_name' => 'Họ và tên của :model',
    'description' => 'Mô tả về :model',
    'phone_number' => 'Số điện thoại',
    'fullname' => 'Họ và tên',
    'province' => 'Tỉnh/ thành phố',
    'district' => 'Quận/ huyện',
    'ward' => 'Thị xã',
    'address' => 'Địa chỉ',
    'dob' => 'Ngày sinh',
    'error' => 'Đã có lỗi xảy ra!',
    'success' => 'Thành công!',
    'password' => 'Mật khẩu',
    'password_confirm' => 'Xác nhận mật khẩu',
    'new_pass' => 'Mật khẩu mới',
    'new_pass_confirm' => 'Xác nhận mật khẩu mới',
    'err_messages' => 'Danh sách lỗi:',
    'succ_messages' => 'Thực hiện :action hoàn tất!',
    'id_label' => 'ID',
    'name' => 'Tên của :model',
    'change_pass' => 'Thay đổi mật khẩu',
    'logout' => 'Đăng xuất',


    'patients' => [

        'label' => 'Bệnh Nhân',
        'plural_label' => 'Bệnh Nhân'

    ],


    'departments' => [
        'title' => 'Quản lý khoa/ phòng ban',
        'label' => 'Khoa',
        'plural_label' => 'Khoa',
        'group' => 'Nhóm chức năng quản lý',
        'place_holder' => 'Chọn khoa',
        'working_doctors' => 'Số bác sĩ trực thuộc',
        'description_view' => 'Xem mô tả',
        'description' => 'Mô tả về :model'


    ],

    'schedule' => [
        'label' => 'Lịch Làm Việc',
        'group' => 'Nhóm chức năng quản lý',
        'title' => 'Quản lý lịch làm việc'
    ],



    'doctors' => [
        'title' => 'Quản lý bác sĩ',
        'label' => 'Bác Sĩ',
        'plural_label' => 'Bác Sĩ',
        'group' => 'Nhóm chức năng quản lý',
        'belongs_depart' => 'Khoa trực thuộc',
        'assign' => 'Phân công',
        'view_cv' => 'Xem CV'

    ],

    'workshifts' => [

        'title' => 'Lịch làm việc của bác sĩ :name',
    ],



    'officers' => [
        'label' => 'Giám Đốc',
        'plural_label' => 'Giám Đốc',
        'group' => 'Nhóm chức năng quản lý',

    ],


    'schedulers' => [
        'label' => 'Điều Phối Viên',
        'plural_label' => 'Điều Phối Viên',
        'group' => 'Nhóm chức năng quản lý',
    ],

    'events' => [
        'title' => 'Tiêu đề',
        'doctors' => 'Danh sách bác sĩ trực',
        'start' => 'Thời gian bắt đầu',
        'end' => 'Thời gian kết thúc',
        'description' => 'Mô tả (nếu có)',
        'time_conflict' => 'Đã có ca trực khác trong khoảng thời gian này!',
        'doctor_conflict' => 'Bác sĩ đã có ca trực trước đó!',
    ],

    'appointments' => [
        'label' => 'Lịch Khám Bệnh',
        'plural_label' => 'Lịch Khám Bệnh',
        'title' => 'Quản lý lịch khám bệnh',
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'canceled' => 'Đã hủy',
        'confirm' => 'Xác nhận',
        'cancel' => 'Hủy bỏ',
        'start' => 'Thời gian bắt đầu',
        'end' => 'Thời gian kết thúc',
        'created_at' => 'Yêu cầu lúc',
        'already_booked' => 'Ca trực này đã có người đặt lịch!',

        'treatments' => [
            'create' => 'Chỉnh sửa bệnh án',
            'view' => 'Xem bệnh án',
            'notes' => 'Kết luận',
            'medication' => 'Đơn thuốc'
        ]

    ],

    'settings' => [
        'group' => 'Cài Đặt'
    ]







];