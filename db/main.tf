module "rds_mysql" {
source            = "git::https://github.com/tmknom/terraform-aws-rds-mysql.git?ref=tags/1.1.0"
identifier        = "example"
engine_version    = "5.7.23"
instance_class    = "db.t2.small"
allocated_storage = 20
username          = "root"
password          = "YouShouldChangePasswordAfterApply!"

subnet_ids          = ["${var.subnets}"]
vpc_id              = "${var.vpc_id}"
ingress_cidr_blocks = ["${var.ingress_cidr_blocks}"]

maintenance_window                  = "mon:10:10-mon:10:40"
backup_window                       = "09:10-09:40"
apply_immediately                   = false
multi_az                            = false
port                                = 3306
name                                = "example"
storage_type                        = "gp2"
iops                                = 0
auto_minor_version_upgrade          = false
allow_major_version_upgrade         = false
backup_retention_period             = 0
storage_encrypted                   = false
kms_key_id                          = ""
deletion_protection                 = false
final_snapshot_identifier           = "final-snapshot"
skip_final_snapshot                 = true
enabled_cloudwatch_logs_exports     = []
monitoring_interval                 = 0
monitoring_role_arn                 = ""
iam_database_authentication_enabled = false
copy_tags_to_snapshot               = false
publicly_accessible                 = true
license_model                       = "general-public-license"
major_engine_version                = "5.7"
description                         = "This is example"

tags = {
    Environment = "prod"
}
}