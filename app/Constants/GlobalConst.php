<?php

namespace App\Constants;

class GlobalConst {
    const USER_PASS_RESEND_TIME_MINUTE = "1";

    const ACTIVE = true;
    const BANNED = false;
    const DEFAULT_TOKEN_EXP_SEC = 3600;

    const VERIFIED      = 1;
    const APPROVED      = 1;
    const PENDING       = 2;
    const REJECTED      = 3;
    const DEFAULT       = 0;
    const UNVERIFIED    = 0;
    const UNKNOWN       = "UNKNOWN";

    const MALE          = "Male";
    const FEMALE        = "Female";
    const OTHERS        = "Others";
    const CASH_PAYMENT  = "Cash Payment";


    const PARLOUR_BOOKING_STATUS_REVIEW_PAYMENT      = 1;
    const PARLOUR_BOOKING_STATUS_PENDING             = 2;
    const PARLOUR_BOOKING_STATUS_CONFIRM_PAYMENT     = 3;
    const PARLOUR_BOOKING_STATUS_HOLD                = 4;
    const PARLOUR_BOOKING_STATUS_SETTLED             = 5;
    const PARLOUR_BOOKING_STATUS_COMPLETE            = 6;
    const PARLOUR_BOOKING_STATUS_CANCEL              = 7;
    const PARLOUR_BOOKING_STATUS_FAILED              = 8;
    const PARLOUR_BOOKING_STATUS_REFUND              = 9;
    const PARLOUR_BOOKING_STATUS_DELAYED             = 10;
    const PARLOUR_BOOKING_STATUS_ALL                 = "ALL";
}