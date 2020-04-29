<?php
/**
 * Copyright (c) KingOfTurkey38 2019.
 * Last Modified 30/03/19 12:00
 *
 * This plugin is developed and maintained by KingOfTurkey38.
 *
 * This plugin is distributed under a commercial license and you are only allowed to use it in the way and
 * extent and under conditions explicitly agreed on with KingOfTurkey38 in writing.
 *
 * By the license, you're not allowed to remove this documented snippet.
 *
 */

declare(strict_types=1);

namespace KingOfTurkey38\Generator\libs\jojoe77777\FormAPI;

use pocketmine\plugin\PluginBase;

class FormAPI extends PluginBase
{

    /**
     * @param callable|null $function
     * @return CustomForm
     * @deprecated
     *
     */
    public function createCustomForm(?callable $function = null): CustomForm
    {
        return new CustomForm($function);
    }

    /**
     * @param callable|null $function
     * @return SimpleForm
     * @deprecated
     *
     */
    public function createSimpleForm(?callable $function = null): SimpleForm
    {
        return new SimpleForm($function);
    }

    /**
     * @param callable|null $function
     * @return ModalForm
     * @deprecated
     *
     */
    public function createModalForm(?callable $function = null): ModalForm
    {
        return new ModalForm($function);
    }
}
