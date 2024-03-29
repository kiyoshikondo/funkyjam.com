INSERT INTO "magazine"(
	account_no,
	mail,
	sex,
	birthday,
	pref,
	password,
	fav_kubota,
	fav_urashima,
	fav_mori,
	fav_bes,
	fav_takahashi,
	fav_shigemoto,
	fav_shima,
	fav_wataru,
	c_stamp,
	u_stamp,
	d_stamp
)
VALUES(
	(SELECT coalesce(max(account_no), 0) + 1 FROM "magazine"),
	'{$form.mail}',
	'{$form.sex|default2:NULL}',
	{if $form.birth_year && $form.birth_month && $form.birth_day}'{$form.birth_year}-{$form.birth_month}-{$form.birth_day}'{else}NULL{/if},
	'{$form.pref|default2:NULL}',
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	current_timestamp,
	current_timestamp,
	NULL
);
