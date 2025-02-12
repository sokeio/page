<?php

namespace Sokeio\Page\Enums;

use Sokeio\Attribute\Label;
use Sokeio\Core\Concerns\AttributableEnum;

enum UIKey: string
{
    use AttributableEnum;
    #[Label('Add menu item')]
    case MENU_ADD_ITEM = 'menu::menu_add_item';
    #[Label('Menu item type')]
    case MENU_ITEM_TYPE = 'menu::menu_item_';
}
