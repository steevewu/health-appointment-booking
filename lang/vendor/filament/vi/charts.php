<?php

return [
    'total_patient_label' => 'Bệnh Nhân Trong Hệ Thống',
    'total_patient_description' => 'Tổng số lượng bệnh nhân hiện tại trong hệ thống.',
    'change_patient_label' => 'Bệnh Nhân Mới Trong Tháng',
    'change_patient_description' => 'so với số lượng bệnh nhân tháng trước.',
    'total_appointment_label' => 'Số Ca Khám Bệnh',
    'total_appointment_description' => 'Tổng số ca khám bệnh đã thực hiện tại phòng khám',
    'change_appointment_label' => 'Ca Khám Bệnh Mới Trong Tháng',
    'change_appointment_description' => 'so với số lượng ca khám bệnh tháng trước',
    'increase' => 'đã tăng',
    'decrease' => 'đã giảm',

    'appointments' => [
        'title' => 'Thống kê ca khámh bệnh',
        'label' => 'Buổi Khám Bệnh',
        'group' => 'Nhóm chức năng báo cáo/ thống kê',

        'distribution' => [
            'title' => 'Biểu đồ thể hiện sự phân bố của các ca khám bệnh theo khoa'
        ],


        'heatmap' => [
            'title' => 'Biểu đồ thể hiện tần suất của các ca khám bệnh theo từng mốc thời gian'
        ],
    ],
    
    
    'patients' => [
        'title' => 'Thống kê bệnh nhân',
        'label' => 'Bệnh Nhân',
        'group' => 'Nhóm chức năng báo cáo/ thống kê',


        'enrollments' => [
            'title' => 'Biểu đồ thể hiện số lượng bệnh nhân mới tham gia vào hệ thống qua từng thời kỳ',
            'x-axis' => 'Tháng',
            'y-axis' => 'Số bệnh nhân'
        ],

        
        'ages' => [
            'title' => 'Biểu đồ thể hiện sự phân bố độ tuổi của bệnh nhân trong hệ thống',
        ],
    ],
    
];