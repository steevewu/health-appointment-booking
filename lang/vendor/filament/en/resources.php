<?php

return [

    'email' => 'Email Address',
    'navigation_label' => ':model Management',
    'full_name' => ':model Full Name',
    'description' => ':model Description',
    'phone_number' => 'Phone Number',
    'fullname' => 'Full Name',
    'province' => 'Province / City',
    'district' => 'District',
    'ward' => 'Ward / Town',
    'address' => 'Address',
    'dob' => 'Date Of Birth',
    'error' => 'An Error Has Occurred!',
    'success' => 'Success!',
    'password' => 'Password',
    'password_confirm' => 'Confirm Password',
    'new_pass' => 'New Password',
    'new_pass_confirm' => 'Confirm New Password',
    'err_messages' => 'Error List:',
    'succ_messages' => ':action Completed Successfully!',
    'id_label' => 'ID',
    'name' => ':model Name',
    'change_pass' => 'Change Password',
    'logout' => 'Logout',

    'patients' => [
        'label' => 'Patient',
        'plural_label' => 'Patients'
    ],

    'departments' => [
        'title' => 'Department Management',
        'label' => 'Department',
        'plural_label' => 'Departments',
        'group' => 'Management Function Group',
        'place_holder' => 'Select Department',
        'working_doctors' => 'Number Of Assigned Doctors',
        'description_view' => 'View Description',
        'description' => ':model Description'
    ],

    'schedule' => [
        'label' => 'Work Schedule',
        'group' => 'Management Function Group',
        'title' => 'Work Schedule Management'
    ],

    'doctors' => [
        'title' => 'Doctor Management',
        'label' => 'Doctor',
        'plural_label' => 'Doctors',
        'group' => 'Management Function Group',
        'belongs_depart' => 'Department',
        'assign' => 'Assign',
        'view_cv' => 'View CV'
    ],

    'workshifts' => [
        'title' => 'Work Schedule Of Doctor :name',
    ],

    'officers' => [
        'label' => 'Director',
        'plural_label' => 'Directors',
        'group' => 'Management Function Group',
    ],

    'schedulers' => [
        'label' => 'Coordinator',
        'plural_label' => 'Coordinators',
        'group' => 'Management Function Group',
    ],

    'events' => [
        'title' => 'Title',
        'doctors' => 'List Of On-duty Doctors',
        'start' => 'Start Time',
        'end' => 'End Time',
        'description' => 'Description (If Any)',
        'time_conflict' => 'There Is Another Shift During This Time!',
        'doctor_conflict' => 'The Doctor Already Has Another Shift Scheduled!',
    ],

    'appointments' => [
        'label' => 'Medical Appointment',
        'plural_label' => 'Medical Appointments',
        'title' => 'Medical Appointment Management',
        'pending' => 'Pending Confirmation',
        'confirmed' => 'Confirmed',
        'canceled' => 'Canceled',
        'confirm' => 'Confirm',
        'submit' => 'Place A Request',
        'cancel' => 'Cancel',
        'start' => 'Start Time',
        'end' => 'End Time',
        'created_at' => 'Requested At',
        'already_booked' => 'This Shift Has Already Been Booked!',
        'heading' => 'Schedule An Appointment With Doctor :name',
        'status' => 'Status',

        'treatments' => [
            'create' => 'Edit Medical Record',
            'view' => 'View Medical Record',
            'notes' => 'Diagnosis / Notes',
            'medication' => 'Prescription',
            'heading' => 'Patient Medical Record Information :name',
            'date' => 'Examination Date',
            'doctor' => 'Examining Doctor'
        ]
    ],

    'settings' => [
        'group' => 'Settings'
    ]

];
