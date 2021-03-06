<?php
/**
 * Timeloop plugin for Craft CMS 3.x
 *
 * This is a plugin to make repeating dates
 *
 * @link      https://percipio.london
 * @copyright Copyright (c) 2021 percipiolondon
 */

namespace percipiolondon\timeloop\assetbundles\companymanagement;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 *
 * @author    percipiolondon
 * @package   CompanyManagement
 * @since     1.0.0
 */
class TimeloopAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@percipiolondon/companymanagement/web/assets/dist";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        parent::init();
    }
}
