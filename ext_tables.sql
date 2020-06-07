#
# Table structure for table 'sys_language'
#
CREATE TABLE sys_language (
    translation_service int(11) unsigned DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_bootstrappackage_card_group_item'
#
CREATE TABLE tx_t3translator_service (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted smallint unsigned DEFAULT '0' NOT NULL,
    hidden smallint unsigned DEFAULT '0' NOT NULL,

    service varchar(255) DEFAULT '' NOT NULL,
    authentication_type varchar(255) DEFAULT '' NOT NULL,
    username varchar(255) DEFAULT '' NOT NULL,
    password varchar(255) DEFAULT '' NOT NULL,
    api_key text,

    PRIMARY KEY (uid),
    KEY parent (pid)
);