<?php

// Text
$_['text_module']       = 'Modules';
$_['text_success']      = 'Success: You have modified HTML Content module!';
$_['text_edit']         = 'Edit HTML Content Module';

// Entry
$_['entry_name']        = 'Module Name';
$_['entry_title']       = 'Heading Title';
$_['entry_description'] = 'Description';
$_['entry_status']      = 'Status';

// Heading
$_['heading_title'] = 'SMSC';

// Tabs
$_['oc_smsc_tab_connection'] = 'Connection';
$_['oc_smsc_tab_member'] = 'Administrator';
$_['oc_smsc_tab_customer'] = 'Customer';

// Text
$_['oc_smsc_text_login'] = 'Login';
$_['oc_smsc_text_password'] = 'Password';
$_['oc_smsc_text_signature'] = 'Sender ID';
$_['oc_smsc_text_maxsms'] = 'Message length';
$_['oc_smsc_text_sms'] = 'SMS';
$_['oc_smsc_text_debug']= 'Debug';
$_['oc_smsc_text_status']= 'status';
$_['oc_smsc_text_admin_new_customer'] = 'The new customer is registered';
$_['oc_smsc_text_admin_new_order'] = 'A new order is implemented';
$_['oc_smsc_text_admin_new_email'] = 'Received new email with store contact form';
$_['oc_smsc_text_telephone'] = 'Phone number';
$_['oc_smsc_text_customer_new_order'] = 'A new order is implemented';
$_['oc_smsc_text_customer_new_order_status'] = 'Change the order status';
$_['oc_smsc_text_customer_new_register'] = 'The registration is completed successfully';
$_['oc_smsc_text_customer_act_phone'] = 'Check phone number';
$_['oc_smsc_text_customer_attention'] = '(<font color=red>attention</font> sets limits in the settings of service)';
$_['oc_smsc_text_notify_by_sms'] = 'Notify me via SMS';
$_['oc_smsc_text_notify'] = 'Texts of the notifications';
$_['oc_smsc_label_admin_new_order'] = 'for new order';
$_['oc_smsc_label_customer_new_order'] = 'for new order';
$_['oc_smsc_label_customer_new_status'] = 'for change the order status';
$_['code_activation'] = 'Activation code';
$_['oc_smsc_text_secret'] = 'Secret string for SMS-code';
$_['oc_smsc_text_call'] = 'Call';

// Messages
$_['oc_smsc_message_customer_new_order_status'] = 'The order status is changed';

// Other
$_['oc_smsc_text_connection_tab_description'] =
'Specify the username and password to connect to the gateway SMSC and sender\'s name.<br />
Note that the sender\'s name should be pre-registered in your account page. To add a<br />
new name to the settings page on the right of the sender\' name choice click "names".';

$_['oc_smsc_text_macros_description'] =
'The text of the SMS-notification may specify special macros that will be replaced<br />
with the appropriate values when sending messages.<br /><br />
For notifications, you can use the following macros:
<ul>
<li>{ORDER_NUM} - order number
<li>{ORDER_SUM} - the total cost of the order (all)
<li>{ORDER_STATUS} - order status
<li>{COMMENT} - comments for the order
<li>{STATUS_COMMENT} - comments from the seller, when the order status is changed 
<li>{FIRST_NAME} - customer firstname
<li>{LAST_NAME} - customer lastname
<li>{CR_PHONE} - phone buyer
<li>{CR_CITY} - city delivery order
<li>{CR_ADDR} - delivery address
<li>{GOODS_LIST} - list of goods in format "Product:Model:Quantity:Total"
</ul>';