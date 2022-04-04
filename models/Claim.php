<?php

namespace app\models;
enum Claim: int
{
    case USER = 1;
    case ADMIN = 2;
}