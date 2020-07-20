<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => ':attribute و تکرار رمز عبور با یکدیگر مطابقت ندارند.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'فرمت ایمیل وارد شده صحیح نمی باشد',
    'mobile' => 'فرمت موبایل وارد شده صحیح نمی باشد',
    'notionalCode' => 'فرمت کد ملی وارد شده صحیح نمی باشد',
    'notional_code' => 'فرمت کد ملی وارد شده صحیح نمی باشد',
    'postalCode' => 'فرمت کد پستی وارد شده صحیح نمی باشد',
    'postal_code' => 'فرمت کد پستی وارد شده صحیح نمی باشد',
    'phone' => 'فرمت تلفن ثابت وارد شده صحیح نمی باشد',
    'latin' => 'لطفا :attribute را بصورت انگلیسی وارد کنید',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => ':attribute نمی‌تواند بیشتر از :value باشد',
        'file' => ':attribute نمی‌تواند بیشتر از :value کیلوبایت باشد.',
        'string' => ':attribute must be greater than :value characters.',
        'array' => ':attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => ':attribute نمی‌تواند بیشتر از :value باشد',
        'file' => ':attribute نمی‌تواند بیشتر از :value کیلوبایت باشد.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => ':attribute باید یک تصویر باشد',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => ':attribute باید یک عدد باشد.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => ':attribute نمی تواند بیشتر از :max کیلوبایت باشد.',
        'string' => ':attribute نمی تواند بیشتر از :max کاراکتر باشد.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'فرمت های مجاز جهت آپلود :attribute : :values',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => ':attribute نمی تواند کمتر از :min کاراکتر باشد.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => ':attribute باید یک عدد باشد.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'فرمت :attribute معتبر نیست.',
    'required' => 'وارد کردن :attribute اجباری است',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute :x must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => ':attribute تکراری می باشد.',
    'uploaded' => ':attribute آپلود نشد.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
	    'attribute-name' => [
		    'rule-name' => 'custom-message',
	    ],
	    'g-recaptcha-response' => [
		    'required' => 'لطفا تأیید کنید که شما یک ربات نیستید',
		    'captcha'  => 'خطای کد امنیتی، دوباره امتحان کنید یا با مدیر سایت تماس بگیرید',
	    ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'subject'               => 'موضوع',
        'message'               => 'پیام',
        'attachments'           => 'ضمیمه ها',
        'attachments.0'         => 'ضمیمه ۱',
        'attachments.1'         => 'ضمیمه ۲',
        'attachments.2'         => 'ضمیمه ۳',
        'attachments.3'         => 'ضمیمه ۴',
        'attachments.4'         => 'ضمیمه ۵',
        'attachments.5'         => 'ضمیمه ۶',
        'attachments.6'         => 'ضمیمه ۷',
        'attachments.7'         => 'ضمیمه ۸',
        'attachments.8'         => 'ضمیمه ۹',
        'comment'               => 'پیام',
        'name_family'           => 'نام و نام خانوادگی',
        'nameFamily'            => 'نام و نام خانوادگی',
        'email'                 => 'ایمیل',
        'mobile'                => 'موبایل',
        'file'                  => 'فایل',
        'gender'                => 'جنسیت',
        'phone'                 => 'تلفن ثابت',
        'family'                => 'نام خانوادگی',
        'teacher'               => 'مدرس',
        'unit_id'               => 'واحد',
        'course_status'         => 'وضعیت دوره',
        'code'                  => 'کد',
        'stock'                 => 'موجودی',
        'price'                 => 'قیمت',
        'coefficient'           => 'ضریب',
        'publishTime'           => 'زمان انتشار',
        'content'               => 'محتوا',
        'description'           => 'توضیح کوتاه',
        'postDisplayPassword'   => 'رمز نمایش پست',
        'specification_type'    => 'نوع مشخصات',
        'slug'                  => 'نامک',
        'post_type'             => 'نوع مطلب',
        'password'              => 'رمز عبور',
        'password_confirmation' => 'تکرار رمز عبور',
        'name'                  => 'نام',
        'title'                 => 'عنوان',
        'label'                 => 'عنوان',
        'nationalCode'          => 'کد ملی',
        'provinceId'            => 'استان',
        'cityId'                => 'شهرستان',
        'townId'                => 'شهر',
        'regionId'              => 'محله',
        'address'               => 'نشانی پستی',
        'postalCode'            => 'کد پستی',
        'keyword'               => 'کلمه کلیدی',
        'excerpt'               => 'توضیح'
    ],

];
