<?php

return [
    'create' => [
        'success' => 'Tạo mới bộ đề thành công',
    ],
    'show' => [
        'success' => 'Lấy thông tin bộ đề thành công'
    ],
    'update' => [
        'success' => 'Cập nhật bộ đề thành công'
    ],
    'delete' => [
        'success' => 'Xoá bộ đề thành công'
    ],
    'validation' => [
        'name' => [
            'required' => 'Trường này không được bỏ trống',
            'unique' => 'Tên này đã có bộ đề sử dụng',
            'string' => 'Tên bộ dề phải để kiểu chuỗi',
            'max' => 'Tên bộ đề không được dài quá 50 từ'
        ],
        'duration' => [
            'required' => 'Trường này không được bỏ trống',
            'string' => 'Thời gian làm bài phải để kiểu chuỗi'
        ],
        'description' => [
            'required' => 'Trường này không được bỏ trống',
            'string' => 'Mô tả phải để kiểu chuỗi'
        ],
        'total_parts' => [
            'required' => 'Trường này không được bỏ trống',
            'integer' => 'Tổng số phần dề phải để kiểu số'
        ],
        'total_questions' => [
            'required' => 'Trường này không được bỏ trống',
            'integer' => 'Tổng số câu hỏi phải để kiểu số'
        ],
        'total_score' => [
            'required' => 'Trường này không được bỏ trống',
            'integer' => 'Số điểm tối đa phải để kiểu số'
        ],
        'status' => [
            'required' => 'Trường này không được bỏ trống',
            'in' => 'Giá trị trạng thái không hợp lệ'
        ],
        'parts' => [
            'partsEqualTotalParts' => 'Tổng số phần đã tạo không trùng khớp với khai báo',
            'questionsEqualTotalQuestions' => 'Tổng số câu hỏi đã tạo không trùng khớp với khai báo',
            'total_questions' => [
                'required' => 'Trường này không được bỏ trống',
                'integer' => 'Tổng số câu hỏi phải để kiểu số'
            ],
            'order_in_test' => [
                'required' => 'Trường này không được bỏ trống',
                'integer' => 'Thứ tự trong đề phải để kiểu số'
            ],
            'part_type' => [
                'required' => 'Trường này không được bỏ trống',
                'in' => 'Dạng đề không hợp lệ'
            ],
            
        ]
    ]
];