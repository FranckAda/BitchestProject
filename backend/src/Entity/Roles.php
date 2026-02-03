<?php

namespace App\Entity;

enum Roles: string
{
  case ADMIN = 'admin';
  case CLIENT = 'client';
}
