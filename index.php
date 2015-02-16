<?php

class UnderscoreDiff
{
    public $destination;
    public $source;
    public $name = '_s';
    public $combineLESS = true;
    public $skipALLCAPSFiles = true;

    public $skipLESSImports = [];

    public function run()
    {
        $this->processPath($this->source);
    }

    /**
     * Replaces underscores
     *
     * 1. Search for '_s' (inside single quotations) to capture the text domain.
     * 1. Search for _s_ to capture all the function names.
     * 1. Search for Text Domain: _s in style.css.
     * 1. Search for  _s (with a space before it) to capture DocBlocks.
     * 1. Search for _s- to capture prefixed handles.
     *
     */
    private function replace($s)
    {
        return str_replace('_s-', "{$this->name}-",
            str_replace(' _s', " {$this->name}",
                str_replace('Text Domain: _s', "Text Domain: {$this->name}",
                    str_replace('_s_', "{$this->name}_",
                        str_replace("'_s'", "'{$this->name}'", $s)
                    )
                )
            )
        );
    }

    private function stop($s)
    {
        echo sprintf("<u>%s</u>\n", $s);
        exit;
    }

    private function copy($from, $to)
    {
        if (!$content = file_get_contents($from)) {
            $this->stop("Failed to load file: `{$from}`.");
        }

        $content = $this->replace($content);

        // Create directory structure
        @mkdir(dirname($to), 0755, true);

        if (!file_put_contents($to, $content)) {
            $this->stop("Failed to write file: `{$to}`.");
        }

        return $this;
    }

    private function processPath($path)
    {
        foreach (glob($path.'/*') as $f) {
            $filename = basename($f);
            $relativePath = str_replace($this->source, '', $f);

            if ($this->skipALLCAPSFiles && strtolower($filename) !== $filename) {
                continue;
            }

            if (is_dir($f)) {
                echo 'DIR:  '.$relativePath."\n";

                if ($this->combineLESS && $filename === 'less') {
                    $less = $this->processLESS($f.'/style.less');

                    if (!file_put_contents($this->destination.'/style.less', $less)) {
                        $this->stop('Failed to write `style.less`.');
                    }

                    continue;
                }

                $this->processPath($f);
            } else {
                if ($filename === 'style.css') {
                    continue;
                }

                $newFile = $this->destination.$relativePath;
                echo 'file: '.$relativePath."\n";

                $this->copy($f, $newFile);
            }
        }
    }

    private function resolveRelativePaths($s)
    {
        $s = str_replace('/./', '/', $s);
        $s = preg_replace('|/[^/]+/../|', '/', $s);

        if (strstr($s, './')) {
            $s = $this->resolveRelativePaths($s);
        }

        return $s;
    }

    private function processLESS($path)
    {
        $root = dirname($path);

        if (!$less = file_get_contents($path)) {
            $this->stop('Missing file: '.str_replace($this->destination, '', $path).'/style.less');
        }

        $less = $this->replace($less);

        $less = preg_replace_callback('|(?<!// )@import ["\'](.+?)[\'"];|', function($matches) use ($root) {

            $lessPath = $root.'/'.$matches[1].'.less';
            $lessPath = $this->resolveRelativePaths($lessPath);

            if ($lessPath && file_exists($lessPath)) {
                if (!in_array($matches[1], (array) $this->skipLESSImports)) {
                    echo '      '.str_replace($this->source, '', $lessPath)."\n";
                    $content = $this->processLESS($lessPath);
                } else {
                    $content = '';
                }

                return "// ".str_replace($this->source, '', $lessPath)."\n{$content}\n";
            }

            $this->stop('LESS does not exist: '.$lessPath);
        }, $less);

        return $less;
    }
}

if (!file_exists('config.php')) {
    if (!copy('_config.php', 'config.php')) {
        die('Failed to create config.php');
    }

    exit('<h1>Success: `config.php` created</h1><p>Now edit it to fit your needs.</p>');
}

require_once 'config.php';
