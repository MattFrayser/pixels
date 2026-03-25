<?php

namespace App;

enum RoomRole: string
{
    case Viewer = 'viewer';
    case Editor = 'editor';
}
