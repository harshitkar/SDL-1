require 'mail'

Mail.defaults do
  delivery_method :smtp, {
    address:              'smtp.gmail.com',
    port:                 587,
    user_name:            'onkarj012@gmail.com',      
    password:             'ncdawmhnokwjwsud', 
    authentication:       :login,
    enable_starttls_auto: true
  }
end

message = Mail.new do
  from    'onkarj012@gmail.com'   
  to      'leoeccentric@gmail.com' 
  subject 'Hello from Ruby!'
  body    'This is a test email sent using Ruby + Gmail App Password.'
end

message.deliver!

puts "Email sent successfully!"
