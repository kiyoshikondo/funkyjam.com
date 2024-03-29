INSERT INTO paygent_diff(
	id,
	success_code,
	success_detail,
	payment_notice_id,
	change_date,
	payment_id,
	trading_id,
	payment_type,
	payment_status,
	ayment_amount,
	payment_init_date,
	payment_limit_date,
	early_notice_date,
	cancel_date,
	user_payment_date,
	ayment_date,
	bank_code,
	cvs_company_id,
	related_payment_id,
	acq_id,
	acq_name,
	joho_code,
	joho_issur_type,
	payment_class,
	summer_bonus,
	winter_bonus,
	split_count,
	issur_name,
	three_d_secure_ryaku,
	fc_auth_nmu,
	daiko_code,
	card_shu_code,
	k_card_name,
	pc_mobile_type,
	career_payment_id
)
VALUES(
	{if $payment_notice_id}{$payment_notice_id}{else}(SELECT coalesce(max(id), 0) + 1 FROM "paygent_diff"){/if},
	'{$success_code}',
	'{$success_detail}',
	'{$payment_notice_id}',
	'{$change_date}',
	'{$payment_id}',
	'{$trading_id}',
	'{$payment_type}',
	'{$payment_status}',
	'{$payment_amount}',
	'{$payment_init_date}',
	'{$payment_limit_date}',
	'{$early_notice_date}',
	'{$cancel_date}',
	'{$user_payment_date}',
	'{$payment_date}',
	'{$bank_code}',
	'{$cvs_company_id}',
	'{$related_payment_id}',
	'{$acq_id}',
	'{$acq_name}',
	'{$joho_code}',
	'{$joho_issur_type}',
	'{$payment_class}',
	'{$summer_bonus}',
	'{$winter_bonus}',
	'{$split_count}',
	'{$issur_name}',
	'{$three_d_secure_ryaku}',
	'{$fc_auth_nmu}',
	'{$daiko_code}',
	'{$card_shu_code}',
	'{$k_card_name}',
	'{$pc_mobile_type}',
	'{$career_payment_id}'
);
