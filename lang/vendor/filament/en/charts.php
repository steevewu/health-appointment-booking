<?php

return [
    'total_patient_label' => 'Patients in the System',
    'total_patient_description' => 'Total number of patients currently in the system.',
    'change_patient_label' => 'New Patients This Month',
    'change_patient_description' => 'compared to the number of patients last month.',
    'total_appointment_label' => 'Number of Medical Appointments',
    'total_appointment_description' => 'Total number of medical appointments performed at the clinic.',
    'change_appointment_label' => 'New Appointments This Month',
    'change_appointment_description' => 'compared to the number of medical appointments last month.',
    'increase' => 'has increased',
    'decrease' => 'has decreased',

    'appointments' => [
        'title' => 'Medical Appointment Statistics',
        'label' => 'Medical Appointment',
        'group' => 'Report/Statistics Function Group',

        'distribution' => [
            'title' => 'Chart showing the distribution of medical appointments by department'
        ],

        'heatmap' => [
            'title' => 'Chart showing the frequency of medical appointments by time period'
        ],
    ],
    
    
    'patients' => [
        'title' => 'Patient Statistics',
        'label' => 'Patient',
        'group' => 'Report/Statistics Function Group',

        'enrollments' => [
            'title' => 'Chart showing the number of new patients joining the system over time',
            'x-axis' => 'Month',
            'y-axis' => 'Number of Patients'
        ],

        
        'ages' => [
            'title' => 'Chart showing the age distribution of patients in the system',
        ],
    ],
    
];
