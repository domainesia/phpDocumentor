<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link http://phpdoc.org
 */

namespace phpDocumentor\Configuration\Definition;

use phpDocumentor\Configuration\SymfonyConfigFactory;
use PHPUnit\Framework\TestCase;
use function getcwd;

final class Version2Test extends TestCase
{
    private const DEFAULT_TEMPLATE_NAME = 'clean';

    /**
     * @dataProvider provideTestConfiguration
     */
    public function testLoadingADefaultConfigWorks(array $inputConfig, array $expectedConfig) : void
    {
        $configuration = new Version2(self::DEFAULT_TEMPLATE_NAME);
        $node = $configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize($inputConfig);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertEquals($expectedConfig, $finalizedConfig);
    }

    public function provideTestConfiguration() : array
    {
        return [
            'default configuration' => [
                [],
                [
                    SymfonyConfigFactory::FIELD_CONFIG_VERSION => '2',
                    'title' => 'my-doc',
                    'parser' => [
                        'default-package-name' => 'Application',
                        'visibility' => ['public', 'protected', 'private'],
                        'target' => 'build/api-cache',
                        'encoding' => 'utf-8',
                        'extensions' => ['extensions' => ['php', 'php3', 'phtml']],
                        'markers' => ['items' => ['TODO', 'FIXME']],
                    ],
                    'files' => [
                        'ignore-hidden' => true,
                        'ignore-symlinks' => true,
                        'directories' => [getcwd()],
                        'files' => [],
                        'ignores' => [],
                    ],
                    'transformer' => ['target' => 'build/api'],
                    'transformations' => [
                        'templates' => [
                            self::DEFAULT_TEMPLATE_NAME => ['name' => self::DEFAULT_TEMPLATE_NAME],
                        ],
                    ],
                    'logging' => [ 'level' => 'error' ],
                ],
            ],
        ];
    }
}