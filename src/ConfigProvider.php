<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog
 */

declare(strict_types=1);

namespace CoiSA\Monolog;

use CoiSA\Monolog\Container;
use Monolog\Handler;

/**
 * Class ConfigProvider
 *
 * @package CoiSA\Monolog
 */
final class ConfigProvider
{
    /**
     * @var string Default handler strategy
     */
    private $strategy;

    /**
     * ConfigProvider constructor.
     *
     * @param string|null $strategy optional Default handler strategy
     */
    public function __construct(?string $strategy = Handler\GroupHandler::class)
    {
        $this->strategy = $strategy;
    }

    /**
     * Returns component config
     *
     * @return array
     */
    public function __invoke(): array
    {
        $config = [
            'dependencies' => [
                'services'  => [
                    ConfigProvider::class => $this
                ]
            ]
        ];

        return array_merge_recursive(
            $config,
            (new Container\ConfigProvider\LoggerConfigProvider)(),
            (new Container\ConfigProvider\StrategiesConfigProvider)(),
            (new Container\ConfigProvider\HandlersConfigProvider)(),
            (new Container\ConfigProvider\ProcessorsConfigProvider)()
        );
    }

    /**
     * Return dependency mappings for this component.
     *
     * @return array
     */
    public function getDependencies(): array
    {
        $config = $this->__invoke();

        return $config['dependencies'];
    }

    /**
     * Returns the default handler strategy
     *
     * @return string
     */
    public function getStrategy()
    {
        return $this->strategy;
    }
}
