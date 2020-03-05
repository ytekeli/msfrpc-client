<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Resource\Module;

use Tightenco\Collect\Support\Collection;
use Ytekeli\MsfRpcClient\Handler\ModuleHandler;

/**
 * @property string $type
 * @property string $name
 * @property string $fullname
 * @property array $advancedOptions
 * @property array $evasionOptions
 * @property array $requiredOptions
 * @property array $runOptions
 */
class ModuleResource
{
    /**
     * @var ModuleHandler
     */
    protected $handler;

    /**
     * @var Collection
     */
    protected $options;

    /**
     * Create a msf module instance.
     *
     * @param Collection    $info
     * @param ModuleHandler $handler
     */
    public function __construct(Collection $info, ModuleHandler $handler)
    {
        $this->type = $info->get('type');
        $this->name = $info->get('name');
        $this->fullname = $info->get('fullname');

        $this->handler = $handler;

        foreach ($info->all() as $key => $value) {
            if (!in_array($key, ['advanced', 'evasion', 'options', 'required', 'runoptions'])) {
                $this->{$key} = $value;
            }
        }

        // Get all options
        $this->options = $this->getOptions();

        // Filter advanced options
        $this->advancedOptions = $this->options->filter(function ($options) {
            return isset($options['advanced']) && $options['advanced'] === true;
        })->keys()->toArray();

        // Filter evasion options
        $this->evasionOptions = $this->options->filter(function ($options) {
            return isset($options['evasion']) && $options['evasion'] === true;
        })->keys()->toArray();

        // Filter required options
        $this->requiredOptions = $this->options->filter(function ($options) {
            return isset($options['required']) && $options['required'] === true;
        })->keys()->toArray();

        // Filter run options
        $this->runOptions = $this->options->filter(function ($options) {
            return isset($options['default']);
        })->mapWithKeys(function ($item, $key) {
            return [$key => $item['default']];
        })->toArray();
    }

    /**
     * Gets all the module options.
     *
     * @return Collection
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * Gets information about the module option.
     *
     * @param string $optionName The option name
     *
     * @return mixed
     */
    public function option(string $optionName = '')
    {
        return $this->options->get($optionName);
    }

    /**
     * Gets advanced options.
     *
     * @return array
     */
    public function advanced()
    {
        return $this->advancedOptions;
    }

    /**
     * Gets evasion options.
     *
     * @return array
     */
    public function evasion()
    {
        return $this->evasionOptions;
    }

    /**
     * Gets required options.
     *
     * @return array
     */
    public function required()
    {
        return $this->requiredOptions;
    }

    /**
     * Gets list of missing required options.
     *
     * @return array
     */
    public function missingRequired()
    {
        return collect($this->requiredOptions)->diff(collect($this->runOptions)->keys()->toArray())->toArray();
    }

    /**
     * Gets run options.
     *
     * @return array
     */
    public function runOptions()
    {
        return $this->runOptions;
    }

    /**
     * @return Collection
     */
    public function getOptions()
    {
        return $this->handler->options($this->type, $this->fullname);
    }

    /**
     * Sets the current option value.
     *
     * @param string $option The option name
     * @param mixed  $value  The option value
     *
     * @return void
     */
    public function setOption(string $option = '', $value = '')
    {
        if (!$this->options->has($option)) {
            throw new \InvalidArgumentException('Invalid option: '.$option);
        }

        $optionType = $this->options->get($option)['type'];

        if ($optionType == 'bool') {
            $optionType = 'boolean';
        }

        $valueType = gettype($value);

        if ($optionType !== $valueType) {
            throw new \InvalidArgumentException(sprintf(
                'Option type must be %s not %s',
                $optionType,
                $valueType
            ));
        }

        $this->runOptions[$option] = $value;
    }

    /**
     * Gets a specific run option.
     *
     * @param string $option
     *
     * @return mixed|null
     */
    public function getOption(string $option = '')
    {
        return isset($this->runOptions[$option]) ? $this->runOptions[$option] : null;
    }
}
