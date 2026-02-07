<?php

use Vanguard\Http\Controllers\CandidateResultController; 
use Vanguard\Http\Controllers\StudentAdmissionController;

// Public route for candidate result page (outside of auth middleware)
Route::get('/', [CandidateResultController::class, 'view'])->name('home'); // Use correct path for CandidateResultController
Route::get('candidate-result', [CandidateResultController::class, 'view'])->name('candidate.result.view');
Route::post('candidate-result/search', [CandidateResultController::class, 'search'])->name('candidate.result.search');

// Student Admission Public routes
Route::get('/form/student-admission', [StudentAdmissionController::class, 'studentAdmissionFrontend'])->name('admission.form');
Route::post('/student-admission', [StudentAdmissionController::class, 'store'])->name('admission.submit');
Route::get('/admission/success/{id}', [StudentAdmissionController::class, 'success'])->name('admission.success');


// Authentication routes (stay inside auth middleware group)
Route::get('login', 'Auth\LoginController@show');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Registration routes with middleware
Route::group(['middleware' => ['registration', 'guest']], function () {
    // Route::get('register', 'Auth\RegisterController@show');
    // Route::post('register', 'Auth\RegisterController@register');

    Route::get('register', function () {
        return redirect('login');
    });
});

// Other authentication-related routes...
Route::emailVerification();

Route::group(['middleware' => ['password-reset', 'guest']], function () {
    Route::resetPassword();
});

// Two-factor authentication routes inside auth middleware...
Route::group(['middleware' => 'two-factor'], function () {
    Route::get('auth/two-factor-authentication', 'Auth\TwoFactorTokenController@show')->name('auth.token');
    Route::post('auth/two-factor-authentication', 'Auth\TwoFactorTokenController@update')->name('auth.token.validate');
});

/**
 * Social Login
 */
// Route::get('auth/{provider}/login', 'Auth\SocialAuthController@redirectToProvider')->name('social.login');
// Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');

/**
 * Impersonate Routes
 */
Route::group(['middleware' => 'auth'], function () {
    Route::impersonate();
});

Route::group(['middleware' => ['auth', 'verified']], function () {

    /**
     * Dashboard
     */

    //Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    /**
     * User Profile
     */

    Route::group(['prefix' => 'profile', 'namespace' => 'Profile'], function () {
        Route::get('/', 'ProfileController@show')->name('profile');
        Route::get('activity', 'ActivityController@show')->name('profile.activity');
        Route::put('details', 'DetailsController@update')->name('profile.update.details');

        Route::post('avatar', 'AvatarController@update')->name('profile.update.avatar');
        Route::post('avatar/external', 'AvatarController@updateExternal')
            ->name('profile.update.avatar-external');

        Route::put('login-details', 'LoginDetailsController@update')
            ->name('profile.update.login-details');

        Route::get('sessions', 'SessionsController@index')
            ->name('profile.sessions')
            ->middleware('session.database');

        Route::delete('sessions/{session}/invalidate', 'SessionsController@destroy')
            ->name('profile.sessions.invalidate')
            ->middleware('session.database');
    });

    /**
     * Two-Factor Authentication Setup
     */

    Route::group(['middleware' => 'two-factor'], function () {
        Route::post('two-factor/enable', 'TwoFactorController@enable')->name('two-factor.enable');

        Route::get('two-factor/verification', 'TwoFactorController@verification')
            ->name('two-factor.verification')
            ->middleware('verify-2fa-phone');

        Route::post('two-factor/resend', 'TwoFactorController@resend')
            ->name('two-factor.resend')
            ->middleware('throttle:1,1', 'verify-2fa-phone');

        Route::post('two-factor/verify', 'TwoFactorController@verify')
            ->name('two-factor.verify')
            ->middleware('verify-2fa-phone');

        Route::post('two-factor/disable', 'TwoFactorController@disable')->name('two-factor.disable');
    });



    /**
     * User Management
     */
    Route::resource('users', 'Users\UsersController')
        ->except('update')->middleware('permission:users.manage');

    Route::group(['prefix' => 'users/{user}', 'middleware' => 'permission:users.manage'], function () {
        Route::put('update/details', 'Users\DetailsController@update')->name('users.update.details');
        Route::put('update/login-details', 'Users\LoginDetailsController@update')
            ->name('users.update.login-details');

        Route::post('update/avatar', 'Users\AvatarController@update')->name('user.update.avatar');
        Route::post('update/avatar/external', 'Users\AvatarController@updateExternal')
            ->name('user.update.avatar.external');

        Route::get('sessions', 'Users\SessionsController@index')
            ->name('user.sessions')->middleware('session.database');

        Route::delete('sessions/{session}/invalidate', 'Users\SessionsController@destroy')
            ->name('user.sessions.invalidate')->middleware('session.database');

        Route::post('two-factor/enable', 'TwoFactorController@enable')->name('user.two-factor.enable');
        Route::post('two-factor/disable', 'TwoFactorController@disable')->name('user.two-factor.disable');
    });

    /**
     * Roles & Permissions
     */
    Route::group(['namespace' => 'Authorization'], function () {
        Route::resource('roles', 'RolesController')->except('show')->middleware('permission:roles.manage');

        Route::post('permissions/save', 'RolePermissionsController@update')
            ->name('permissions.save')
            ->middleware('permission:permissions.manage');

        Route::resource('permissions', 'PermissionsController')->middleware('permission:permissions.manage');
    });


    /**
     * Settings
     */

    Route::get('settings', 'SettingsController@general')->name('settings.general')
        ->middleware('permission:settings.general');

    Route::post('settings/general', 'SettingsController@update')->name('settings.general.update')
        ->middleware('permission:settings.general');

    Route::get('settings/auth', 'SettingsController@auth')->name('settings.auth')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth', 'SettingsController@update')->name('settings.auth.update')
        ->middleware('permission:settings.auth');

    if (config('services.authy.key')) {
        Route::post('settings/auth/2fa/enable', 'SettingsController@enableTwoFactor')
            ->name('settings.auth.2fa.enable')
            ->middleware('permission:settings.auth');

        Route::post('settings/auth/2fa/disable', 'SettingsController@disableTwoFactor')
            ->name('settings.auth.2fa.disable')
            ->middleware('permission:settings.auth');
    }

    Route::post('settings/auth/registration/captcha/enable', 'SettingsController@enableCaptcha')
        ->name('settings.registration.captcha.enable')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth/registration/captcha/disable', 'SettingsController@disableCaptcha')
        ->name('settings.registration.captcha.disable')
        ->middleware('permission:settings.auth');

    Route::get('settings/notifications', 'SettingsController@notifications')
        ->name('settings.notifications')
        ->middleware('permission:settings.notifications');

    Route::post('settings/notifications', 'SettingsController@update')
        ->name('settings.notifications.update')
        ->middleware('permission:settings.notifications');

    /**
     * Activity Log
     */

    Route::get('activity', 'ActivityController@index')->name('activity.index')
        ->middleware('permission:users.activity');

    Route::get('activity/user/{user}/log', 'Users\ActivityController@index')->name('activity.user')
        ->middleware('permission:users.activity');

    /**
     * Departments
     */
    Route::get('departments', 'DepartmentController@index')->name('departments.index');
    Route::get('departments/create', 'DepartmentController@create')->name('departments.create');
    Route::post('departments', 'DepartmentController@store')->name('departments.store');
    Route::get('departments/{department}/edit', 'DepartmentController@edit')->name('departments.edit');
    Route::put('departments/{department}', 'DepartmentController@update')->name('departments.update');
    Route::delete('departments/{department}', 'DepartmentController@destroy')->name('departments.destroy');

    /**
     * Employees
     */
    Route::get('employees', 'EmployeeController@index')->name('employees.index');
    Route::get('employees/create', 'EmployeeController@create')->name('employees.create');
    Route::post('employees', 'EmployeeController@store')->name('employees.store');
    Route::get('employees/{employee}/edit', 'EmployeeController@edit')->name('employees.edit');
    Route::put('employees/{employee}', 'EmployeeController@update')->name('employees.update');
    Route::delete('employees/{employee}', 'EmployeeController@destroy')->name('employees.destroy');

    /**
     * Admissions
     */
    Route::get('admissions', 'AdmissionController@index')->name('admissions.index');
    Route::get('admissions/create', 'AdmissionController@create')->name('admissions.create');
    Route::post('admissions', 'AdmissionController@store')->name('admissions.store');
    Route::get('admissions/{admission}', 'AdmissionController@show')->name('admissions.show'); // New route for show action
    Route::get('admissions/{admission}/edit', 'AdmissionController@edit')->name('admissions.edit');
    Route::put('admissions/{admission}', 'AdmissionController@update')->name('admissions.update');
    Route::delete('admissions/{admission}', 'AdmissionController@destroy')->name('admissions.destroy');
    Route::get('admissions/print/{admission}', 'AdmissionController@print')->name('admissions.print');
    Route::get('/api/courses/{id}','AdmissionController@getCourseDetails');
    Route::get('/api/batches/course/{id}', 'AdmissionController@getBatchDetails');

    Route::get('admissions/collect-due/{billId}', 'AdmissionController@showDueCollectionForm')->name('admissions.show-due-collection-form');
    Route::post('admissions/collect-due/{id}', 'AdmissionController@collectDue')->name('admissions.collect-due');

    // Payment Invoice Routes
    Route::get('payment-invoices', [StudentAdmissionController::class, 'paymentInvoicesIndex'])
        ->name('student-admissions.payment-invoices');

    Route::get('payment-invoice/create', [StudentAdmissionController::class, 'paymentInvoiceForm'])
        ->name('student-admissions.payment-invoice-form');

    Route::post('payment-invoice', [StudentAdmissionController::class, 'storePaymentInvoice'])
        ->name('student-admissions.store-payment-invoice');

    Route::get('search-student', [StudentAdmissionController::class, 'searchStudent'])
        ->name('student-admissions.search-student');

    Route::get('student-admissions/{id}/details', [StudentAdmissionController::class, 'getStudentDetails'])
        ->name('student-admissions.student-details');

    Route::get('payment-invoice/{id}/receipt', [StudentAdmissionController::class, 'paymentInvoiceReceipt'])
        ->name('student-admissions.payment-invoice-receipt');

    Route::get('payment-invoice/{id}/download-pdf', [StudentAdmissionController::class, 'downloadPaymentInvoicePdf'])
        ->name('student-admissions.download-payment-invoice-pdf');    
    /**
         * Student Admissions Admin Routes - MOVE THESE INSIDE AUTH
         */
        // Route::get('student-admissions', [StudentAdmissionController::class, 'index'])
        //     ->name('student-admissions.index');

        // Route::get('student-admissions/{id}', [StudentAdmissionController::class, 'show'])
        //     ->name('student-admissions.show');

        // Route::post('student-admissions/{id}/status', [StudentAdmissionController::class, 'updateStatus'])
        //     ->name('student-admissions.update-status');

        // Route::delete('student-admissions/{id}', [StudentAdmissionController::class, 'destroy'])
        //     ->name('student-admissions.destroy');

        // Route::post('student-admissions/{id}/approve', [StudentAdmissionController::class, 'approveAdmission'])
        //     ->name('student-admissions.approve');

        // Route::get('student-admissions/{id}/batches', [StudentAdmissionController::class, 'getCourseBatches'])
        //     ->name('student-admissions.course-batches');

        // Student Admissions Admin Routes
        Route::get('student-admissions', [StudentAdmissionController::class, 'index'])
            ->name('student-admissions.index');

        Route::get('student-admissions-pending', [StudentAdmissionController::class, 'PendingStudentIndex'])
            ->name('student-admissions.pending-student-index');

        Route::get('student-admissions/{id}', [StudentAdmissionController::class, 'show'])
            ->name('student-admissions.show');

        // Add this route
        Route::get('student-admissions/{studentAdmission}/edit', [StudentAdmissionController::class, 'edit'])
            ->name('student-admissions.edit');

        Route::put('student-admissions/{studentAdmission}', [StudentAdmissionController::class, 'update'])
            ->name('student-admissions.update');

        Route::post('student-admissions/{id}/status', [StudentAdmissionController::class, 'updateStatus'])
            ->name('student-admissions.update-status');

        Route::delete('student-admissions/{id}', [StudentAdmissionController::class, 'destroy'])
            ->name('student-admissions.destroy');

        Route::post('student-admissions/{id}/approve', [StudentAdmissionController::class, 'approveAdmission'])
            ->name('student-admissions.approve');

        Route::get('student-admissions/{id}/batches', [StudentAdmissionController::class, 'getCourseBatches'])
            ->name('student-admissions.course-batches');

            // Student ID Card Routes within StudentAdmissionController
        Route::get('student-admissions/{id}/id-card', [StudentAdmissionController::class, 'showIdCard'])
            ->name('student-admissions.id-card');

        Route::get('student-admissions/{id}/download-id-card', [StudentAdmissionController::class, 'downloadIdCardPdf'])
            ->name('student-admissions.download-id-card');
        Route::get('student-admissions/{id}/download-id-card-image', [StudentAdmissionController::class, 'downloadIdCardImage'])
    ->name('student-admissions.download-id-card-image');

        Route::post('student-admissions/bulk-id-cards', [StudentAdmissionController::class, 'bulkIdCards'])
            ->name('student-admissions.bulk-id-cards');

            // Daily Revenue Report Routes

    Route::get('daily-revenue', [StudentAdmissionController::class, 'dailyRevenue'])
    ->name('student-admissions.daily-revenue');


    Route::get('student-admissions/daily-revenue/pdf', [StudentAdmissionController::class, 'exportDailyRevenuePdf'])
        ->name('student-admissions.daily-revenue.pdf');
    Route::get('student-admissions/daily-revenue/excel', [StudentAdmissionController::class, 'exportDailyRevenueExcel'])
        ->name('student-admissions.daily-revenue.excel');


    
    
    // Get batches by course (AJAX)
    Route::get('student-admissions/batches-by-course/{courseId}', 
        [StudentAdmissionController::class, 'getBatchesByCourse'])
        ->name('student-admissions.batches-by-course');

    // Get all active batches (AJAX)
    Route::get('batches/active', [BatchController::class, 'getActiveBatches'])
        ->name('batches.active');

/**
     * Courses
     */
    Route::get('courses', 'CourseController@index')->name('courses.index');
    Route::get('courses/create', 'CourseController@create')->name('courses.create');
    Route::post('courses', 'CourseController@store')->name('courses.store');
    Route::get('courses/{course}/edit',  'CourseController@edit')->name('courses.edit');
    Route::put('courses/{course}', 'CourseController@update')->name('courses.update');
    Route::delete('courses/{course}',  'CourseController@destroy')->name('courses.destroy');

      /**
     * Batches
     */
    Route::get('batches', 'BatchController@index')->name('batches.index');
    Route::get('batches/create', 'BatchController@create')->name('batches.create');
    Route::post('batches', 'BatchController@store')->name('batches.store');
    Route::get('batches/{batch}/edit',  'BatchController@edit')->name('batches.edit');
    Route::put('batches/{batch}', 'BatchController@update')->name('batches.update');
    Route::delete('batches/{batch}',  'BatchController@destroy')->name('batches.destroy');
    Route::get('batches/check-code', 'BatchController@checkBatchCode')->name('batches.check-code');

    /**
 * Mock Test Results
 */
    Route::get('mock-test-results', 'MockTestResultController@index')->name('mock_test_results.index');
    Route::get('mock-test-results/create', 'MockTestResultController@create')->name('mock_test_results.create');
    Route::post('mock-test-results', 'MockTestResultController@store')->name('mock_test_results.store');
    Route::get('mock-test-results/{mockTestResult}/edit', 'MockTestResultController@edit')->name('mock_test_results.edit');
    Route::put('mock-test-results/{mockTestResult}', 'MockTestResultController@update')->name('mock_test_results.update');
    Route::delete('mock-test-results/{mockTestResult}', 'MockTestResultController@destroy')->name('mock_test_results.destroy');
    // Import view route
    Route::get('mock-test-results/import', 'MockTestResultController@importForm')->name('mock_test_results.import_form');
    
    // Import route for handling the form submission
    Route::post('mock-test-results/import', 'MockTestResultController@import')->name('mock_test_results.import');

    /**
     * Mock Test Time Slots
     */
    Route::get('mock-test-time-slots', 'MockTestTimeSlotController@index')->name('mock_test_time_slots.index');
    Route::get('mock-test-time-slots/create', 'MockTestTimeSlotController@create')->name('mock_test_time_slots.create');
    Route::post('mock-test-time-slots', 'MockTestTimeSlotController@store')->name('mock_test_time_slots.store');
    Route::get('mock-test-time-slots/{mockTestTimeSlot}/edit', 'MockTestTimeSlotController@edit')->name('mock_test_time_slots.edit');
    Route::put('mock-test-time-slots/{mockTestTimeSlot}', 'MockTestTimeSlotController@update')->name('mock_test_time_slots.update');
    Route::delete('mock-test-time-slots/{mockTestTimeSlot}', 'MockTestTimeSlotController@destroy')->name('mock_test_time_slots.destroy');

     /**
     * Mock Status
     */

     Route::get('mock-test-statuses', 'MockTestStatusController@index')->name('mock_test_statuses.index');
     Route::get('mock-test-statuses/create', 'MockTestStatusController@create')->name('mock_test_statuses.create');
     Route::post('mock-test-statuses', 'MockTestStatusController@store')->name('mock_test_statuses.store');
     Route::get('mock-test-statuses/{mockTestStatus}/edit', 'MockTestStatusController@edit')->name('mock_test_statuses.edit');
     Route::put('mock-test-statuses/{mockTestStatus}', 'MockTestStatusController@update')->name('mock_test_statuses.update');
     Route::delete('mock-test-statuses/{mockTestStatus}', 'MockTestStatusController@destroy')->name('mock_test_statuses.destroy');
     

        /**
     * Mock Test Dates
     */
    Route::get('mock-test-dates', 'MockTestDateController@index')->name('mock_test_dates.index');
    Route::get('mock-test-dates/create', 'MockTestDateController@create')->name('mock_test_dates.create');
    Route::post('mock-test-dates', 'MockTestDateController@store')->name('mock_test_dates.store');
    Route::get('mock-test-dates/{mockTestDate}/edit', 'MockTestDateController@edit')->name('mock_test_dates.edit');
    Route::put('mock-test-dates/{mockTestDate}', 'MockTestDateController@update')->name('mock_test_dates.update');
    Route::delete('mock-test-dates/{mockTestDate}', 'MockTestDateController@destroy')->name('mock_test_dates.destroy');

        /**
     * Mock Test Rooms
     */
    Route::get('mock-test-rooms', 'MockTestRoomController@index')->name('mock_test_rooms.index');
    Route::get('mock-test-rooms/create', 'MockTestRoomController@create')->name('mock_test_rooms.create');
    Route::post('mock-test-rooms', 'MockTestRoomController@store')->name('mock_test_rooms.store');
    Route::get('mock-test-rooms/{mockTestRoom}/edit', 'MockTestRoomController@edit')->name('mock_test_rooms.edit');
    Route::put('mock-test-rooms/{mockTestRoom}', 'MockTestRoomController@update')->name('mock_test_rooms.update');
    Route::delete('mock-test-rooms/{mockTestRoom}', 'MockTestRoomController@destroy')->name('mock_test_rooms.destroy');

        /**
     * Mock Test Registrations
     */
    Route::get('mock-test-registrations', 'MockTestRegistrationController@index')->name('mock_test_registrations.index');
    Route::get('mock-test-registrations/create', 'MockTestRegistrationController@create')->name('mock_test_registrations.create');
    Route::post('mock-test-registrations', 'MockTestRegistrationController@store')->name('mock_test_registrations.store');
    Route::get('mock-test-registrations/{mockTestRegistration}/edit', 'MockTestRegistrationController@edit')->name('mock_test_registrations.edit');
    Route::put('mock-test-registrations/{mockTestRegistration}', 'MockTestRegistrationController@update')->name('mock_test_registrations.update');
    Route::delete('mock-test-registrations/{mockTestRegistration}', 'MockTestRegistrationController@destroy')->name('mock_test_registrations.destroy');

    // Additional routes
    Route::get('mock-test-registrations/{registration}/token', 'MockTestRegistrationController@generateToken')->name('mock_test_registrations.token');
    Route::get('mock-test-registrations/{registration}/email', 'MockTestRegistrationController@sendEmail')->name('mock_test_registrations.email');

    // IoC routes
    Route::get('mock-test-registrations-ioc', 'MockTestRegistrationController@indexioc')->name('mock_test_registrations.indexioc');
    Route::get('mock-test-registrations/createioc', 'MockTestRegistrationController@createIoc')->name('mock_test_registrations.createioc');
    Route::post('mock-test-registrations-ioc', 'MockTestRegistrationController@storeIoc')->name('mock_test_registrations.storeioc');
    Route::get('mock-test-registrations/{mockTestRegistration}/editioc', 'MockTestRegistrationController@editIoc')->name('mock_test_registrations.editioc');
    Route::put('mock-test-registrations/{mockTestRegistration}/updateioc', 'MockTestRegistrationController@updateIoc')->name('mock_test_registrations.updateioc');
 
   // check duplicate email for mocktest regisration
    Route::get('mock-test-registrations/check-duplicate', [MockTestRegistrationController::class, 'checkDuplicate'])
    ->name('mock_test_registrations.check-duplicate');   
    /**
     * Another Day Bookings
     */
    Route::get('another-days', 'AnotherDayController@index')->name('another_days.index');
    Route::get('another-days/create', 'AnotherDayController@create')->name('another_days.create');
    Route::post('another-days', 'AnotherDayController@store')->name('another_days.store');
    Route::get('another-days/{anotherDay}/edit', 'AnotherDayController@edit')->name('another_days.edit');
    Route::put('another-days/{anotherDay}', 'AnotherDayController@update')->name('another_days.update');
    Route::delete('another-days/{anotherDay}', 'AnotherDayController@destroy')->name('another_days.destroy');
    /**
     * Another Day Import
     */
    Route::get('another-days/import', 'AnotherDayController@showImportForm')->name('another_days.import_form');
    Route::post('another-days/import', 'AnotherDayController@import')->name('another_days.import');
    Route::get('another-days/track-email', 'AnotherDayController@trackEmail')->name('another_days.email.track');


    // Email report
    Route::get('another-days/email-report', 'AnotherDayController@emailReport')->name('another_days.email.report');

});
