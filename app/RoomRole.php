<?php

namespace App;

enum RoomRole: string
{
    case Viewer = 'viewer';
    case Owner = 'owner';
    case Editor = 'editor';
}
