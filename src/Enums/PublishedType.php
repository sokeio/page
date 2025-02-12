<?php

namespace Sokeio\Page\Enums;

use Sokeio\Core\Attribute\Label;
use Sokeio\Core\Concerns\AttributableEnum;

enum PublishedType: string
{
    use AttributableEnum;
    #[Label('Published')]
    case PUBLISHED = 'published';
    #[Label('Draft')]
    case DRAFT = 'draft';
    #[Label('Scheduled')]
    case SCHEDULED = 'scheduled';
}
