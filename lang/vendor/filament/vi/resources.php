<?php

return [

    'email' => 'Địa Chỉ Email',
    'navigation_label' => 'Quản Lý :model',
    'full_name' => 'Họ Và Tên :model',
    'description' => 'Mô Tả Về :model',
    'phone_number' => 'Số Điện Thoại',
    'fullname' => 'Họ Và Tên',
    'province' => 'Tỉnh/ Thành Phố',
    'district' => 'Quận/ Huyện',
    'ward' => 'Thị Xã',
    'address' => 'Địa Chỉ',
    'dob' => 'Ngày Sinh',
    'error' => 'Đã Có Lỗi Xảy Ra!',
    'success' => 'Thành Công!',
    'password' => 'Mật Khẩu',
    'password_confirm' => 'Xác Nhận Mật Khẩu',
    'new_pass' => 'Mật Khẩu Mới',
    'new_pass_confirm' => 'Xác Nhận Mật Khẩu Mới',
    'err_messages' => 'Danh Sách Lỗi:',
    'succ_messages' => 'Thực Hiện :action Hoàn Tất!',
    'id_label' => 'ID',
    'name' => 'Tên Của :model',
    'change_pass' => 'Thay Đổi Mật Khẩu',
    'logout' => 'Đăng Xuất',

    'patients' => [
        'label' => 'Bệnh Nhân',
        'plural_label' => 'Bệnh Nhân',
    ],

    'departments' => [
        'title' => 'Quản Lý Khoa/ Phòng Ban',
        'label' => 'Khoa',
        'plural_label' => 'Khoa',
        'group' => 'Nhóm Chức Năng Quản Lý',
        'place_holder' => 'Chọn Khoa',
        'working_doctors' => 'Số Bác Sĩ Trực Thuộc',
        'description_view' => 'Xem Mô Tả',
        'description' => 'Mô Tả Về :model',
    ],

    'schedule' => [
        'label' => 'Lịch Làm Việc',
        'group' => 'Nhóm Chức Năng Quản Lý',
        'title' => 'Quản Lý Lịch Làm Việc',
    ],

    'doctors' => [
        'title' => 'Quản Lý Bác Sĩ',
        'label' => 'Bác Sĩ',
        'plural_label' => 'Bác Sĩ',
        'group' => 'Nhóm Chức Năng Quản Lý',
        'belongs_depart' => 'Khoa Trực Thuộc',
        'assign' => 'Phân Công',
        'view_cv' => 'Xem CV',
    ],

    'workshifts' => [
        'title' => 'Lịch Làm Việc Của Bác Sĩ :name',
    ],

    'officers' => [
        'label' => 'Giám Đốc',
        'plural_label' => 'Giám Đốc',
        'group' => 'Nhóm Chức Năng Quản Lý',
    ],

    'schedulers' => [
        'label' => 'Điều Phối Viên',
        'plural_label' => 'Điều Phối Viên',
        'group' => 'Nhóm Chức Năng Quản Lý',
    ],

    'events' => [
        'title' => 'Tiêu Đề',
        'doctors' => 'Danh Sách Bác Sĩ Trực',
        'start' => 'Thời Gian Bắt Đầu',
        'end' => 'Thời Gian Kết Thúc',
        'description' => 'Mô Tả (Nếu Có)',
        'time_conflict' => 'Đã Có Ca Trực Khác Trong Khoảng Thời Gian Này!',
        'doctor_conflict' => 'Bác Sĩ Đã Có Ca Trực Trước Đó!',
    ],

    'appointments' => [
        'label' => 'Lịch Khám Bệnh',
        'plural_label' => 'Lịch Khám Bệnh',
        'title' => 'Quản Lý Lịch Khám Bệnh',
        'pending' => 'Chờ Xác Nhận',
        'confirmed' => 'Đã Xác Nhận',
        'canceled' => 'Đã Hủy',
        'submit' => 'Đặt Lịch Hẹn',
        'confirm' => 'Xác Nhận',
        'cancel' => 'Hủy Bỏ',
        'start' => 'Thời Gian Bắt Đầu',
        'end' => 'Thời Gian Kết Thúc',
        'created_at' => 'Yêu Cầu Lúc',
        'already_booked' => 'Ca Trực Này Đã Có Người Đặt Lịch!',
        'heading' => 'Đặt Lịch Thăm Khám Với Bác Sĩ :name',

        'treatments' => [
            'create' => 'Chỉnh Sửa Bệnh Án',
            'view' => 'Xem Bệnh Án',
            'notes' => 'Kết Luận',
            'medication' => 'Đơn Thuốc',
            'heading' => 'Thông Tin Bệnh Án Của Bệnh Nhân :name',
            'date' => 'Ngày Thăm Khám',
            'doctor' => 'Bác Sĩ Thăm Khám',
        ],
    ],

    'settings' => [
        'group' => 'Cài Đặt',
    ],

];
