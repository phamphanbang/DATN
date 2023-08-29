<?php

return [
    'user' => [
        'name' => [
            'required' => 'Trường này không được bỏ trống',
            'unique' => 'Tên này đã có người sử dụng',
            'min' => 'Tên cần phải dài hơn 8 ký tự',
            'max' => 'Tên cần phải ngắn hơn 50 ký tự'
        ],
        'email' => [
            'required' => 'Trường này không được bỏ trống',
            'email' => 'Định dạng email không hợp lệ',
            'unique' => 'Email này đã có người sử dụng'
        ],
        'password' => [
            'required' => 'Trường này không được bỏ trống',
            'string' => 'Trường này chỉ nhận kiểu dữ liệu dạng chữ',
            'min' => 'Mật khẩu cần dài hơn 8 ký tự',
            'max' => 'Mật khẩu cần ngắn hơn 20 ký tự'
        ],
        'avatar' => [
            'image' => 'Ảnh đại diện phải để kiểu ảnh'
        ],
        'panel' => [
            'image' => 'Ảnh đại diện phải để kiểu ảnh'
        ],
        'role' => [
            'required' => 'Trường này không được bỏ trống',
        ]

    ]
];
