@component('mail::message')
# Xác Thực Email

Cảm ơn bạn đã đăng ký tài khoản tại <b><i>Phenikaa Clinic</i></b>!

Chúng tôi rất vui mừng chào đón bạn đến với cộng đồng chăm sóc sức khỏe của chúng tôi. Việc tạo tài khoản giúp bạn dễ dàng đặt lịch hẹn, quản lý thông tin sức khỏe cá nhân, và truy cập các dịch vụ y tế của phòng khám.

Để hoàn tất quá trình đăng ký và kích hoạt tài khoản, vui lòng nhấp vào nút xác thực bên dưới:
@component('mail::button', ['url' => $url])
Xác thực tài khoản
@endcomponent

Nếu nút không hoạt động, bạn có thể sao chép và dán đường dẫn sau vào trình duyệt của mình: <a href="{{$url}}">đường dẫn</a>.

<hr>

<b>Bạn không thực hiện việc đăng ký?<b>

Nếu bạn không phải là người đã yêu cầu đăng ký tài khoản này, vui lòng bỏ qua email này. Tài khoản của bạn sẽ không được kích hoạt trừ khi quá trình xác thực được hoàn tất.

Nếu bạn gặp bất kỳ vấn đề nào trong quá trình xác thực hoặc cần hỗ trợ thêm, đừng ngần ngại liên hệ với chúng tôi qua email này hoặc gọi đến số hotline: 0123.456.789.

<hr>
<b>Trân trọng</b>,<br>
Đội ngũ <b><i>Phenikaa Clinic</i></b><br>
clinic.phenikaa.vn<br>
0123.456.789
@endcomponent
