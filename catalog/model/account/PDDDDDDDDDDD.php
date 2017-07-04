<?php

Uttuyen 5acc 24tr
Dunghanh 5acc 12tr
Quocthien 10acc 24tr
Quocan 12tr 5 acc
Kim oanh 6tr,
phicong 6tr
Thanhtuy 24tr
thanhthuy1,2,3 12tr
thainhi, 1,2,3 12tr
thainhi4 6tr

'Dunghanh','Dunghanh1','Dunghanh2','Dunghanh3','Dunghanh4'
'Quocan','Quocan1','Quocan2','Quocan3','Quocan4'
'thanhthuy3','thanhthuy2','thanhthuy1'
'thainhi','Thainhi1','Thainhi2','Thainhi3'

'Uttuyen','Uttuyen1','Uttuyen2','Uttuyen3','Uttuyen4'
'Quocthien','Quocthien1','Quocthien2','Quocthien3','Quocthien4','Quocthien5','Quocthien6','Quocthien7','Quocthien8','Quocthien9'
'thanhthuy',

'Kimoanh','Phicong','Thainhi4'

204000000
384000000
18000000

//New user rq
1.
SELECT * FROM `sm_customer` WHERE `username` IN (
'Thanh Trúc'
'Thanh Trúc1'
'Thanh Trúc2'
'Thanh Trúc3'
'Thanh Trúc4'
'Quynhchau3'
'Quynhchau1'
'Quynhchau2'
'Quynhchau4'
'Quynhchau5'
'Quynhchau1'
'Quynhchau'
)


2.
SELECT * FROM `sm_customer_provide_donation` WHERE 
`customer_id` IN (SELECT customer_id FROM `sm_customer` WHERE `username` IN (
'thutrang',
'lethituanh',
'haiyen',
'Kyviet',
'Kyviet1',
'Kyviet2',
'Kyviet3',
'Kyviet4',
'Tran Hong Viet1',
'Thanh Trúc',
'Thanh Trúc1',
'Thanh Trúc2',
'Thanh Trúc3',
'Thanh Trúc4',
'Quynhchau',
'Quynhchau1',
'Quynhchau2',
'Quynhchau3',
'Quynhchau4',
'Quynhchau5'

)) and status <> 2

3.
//Update date_added PD
UPDATE `sm_customer_provide_donation` SET date_added = DATE_ADD(date_added,INTERVAL -15 DAY) WHERE 
`customer_id` IN (SELECT customer_id FROM `sm_customer` WHERE `username` IN (
'hungphuong',
'hungphuong1',
'tuyetvan1',
'tuyetvan2',
'Mailuu',
'Mailuu02',
'Mailuu03',
'Mailuu04',
'Mailuu05',
'ngohuong',
'dangthai1',
'dangthai2',
'dangthai3',
'dangthai4',
'non',
'non1',
'non2',
'non3',
'non4',
'Kimquanh',
'Bichtuyen',
'Bichtuyen1',
'Bichtuyen2',
'Haidang01',
'Haidang02',
'Haidang03',
'Haidang04',
'Haidang05'
)) and status <> 2


thanhluong1

4. autoPD
5.
//Update status + check_ Rwallet
UPDATE `sm_customer_provide_donation` SET `status`=2, `check_R_Wallet` =1 WHERE 
`customer_id` IN (SELECT customer_id FROM `sm_customer` WHERE `username` IN (
'hungphuong',
'hungphuong1',
'tuyetvan1',
'tuyetvan2',
'Mailuu',
'Mailuu02',
'Mailuu03',
'Mailuu04',
'Mailuu05',
'ngohuong',
'dangthai1',
'dangthai2',
'dangthai3',
'dangthai4',
'non',
'non1',
'non2',
'non3',
'non4',
'Kimquanh',
'Bichtuyen',
'Bichtuyen1',
'Bichtuyen2',
'Haidang01',
'Haidang02',
'Haidang03',
'Haidang04',
'Haidang05'

)) and status <> 2



6.
// GET transfer list by ID PD
	SELECT * FROM `sm_customer_transfer_list` 
	WHERE `pd_id` IN (SELECT id FROM `sm_customer_provide_donation` WHERE `check_R_Wallet` = 1)

7.
//Update status tfl, image, date_added

UPDATE `sm_customer_transfer_list` SET `pd_satatus` = 1, `gd_status` = 1, 
`image` = 'http://iops.biz/system/upload/2_logo.png.2c76b52405514b9063b2587c4d806add', 
`date_finish` = DATE_ADD(date_finish,INTERVAL -1 DAY) 
WHERE `pd_id` IN (SELECT id FROM `sm_customer_provide_donation` WHERE `check_R_Wallet` = 1)

