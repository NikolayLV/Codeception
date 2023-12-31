<?php

declare(strict_types=1);

namespace Codeception\Lib\Generator;

use Codeception\Exception\ConfigurationException;
use Codeception\Util\Template;

class Cept
{
    protected string $template = <<<EOF
<?php {{use}}
\$I = new {{actor}}(\$scenario);
\$I->wantTo('perform actions and see result');

EOF;

    protected array $settings = [];

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function produce(): string
    {
        $actor = $this->settings['actor'];
        if (!$actor) {
            throw new ConfigurationException("Cept can't be created for suite without an actor. Add `actor: SomeTester` to suite config");
        }
        $use = '';
        if (! empty($this->settings['namespace'])) {
            $namespace = rtrim($this->settings['namespace'], '\\');
            $use = "use {$namespace}\\{$actor};";
        }

        return (new Template($this->template))
            ->place('actor', $actor)
            ->place('use', $use)
            ->produce();
    }
}
