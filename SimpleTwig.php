<?php

class SimpleTwig {
    private $templateDir;
    private $cache = [];

    public function __construct($templateDir) {
        $this->templateDir = $templateDir;
    }

    public function render($template, $data = []) {
        return $this->renderTemplate($template, $data);
    }

    private function renderTemplate($template, $data = []) {
        $templatePath = $this->templateDir . '/' . $template;

        if (!file_exists($templatePath)) {
            return "<h1>Template not found: $template</h1>";
        }

        $content = file_get_contents($templatePath);

        // Handle extends
        if (preg_match('/\{% extends \'([^\']+)\' %\}/', $content, $matches)) {
            $parentTemplate = $matches[1];
            $parentContent = $this->renderTemplate($parentTemplate, $data);

            // Extract blocks from child template
            $blocks = $this->extractBlocks($content);

            // Replace block placeholders in parent
            foreach ($blocks as $blockName => $blockContent) {
                $parentContent = str_replace("{% block $blockName %}{% endblock %}", $blockContent, $parentContent);
            }

            $content = $parentContent;
        }

        // Basic variable replacement using {{ variable }}
        foreach ($data as $key => $value) {
            $content = str_replace("{{ $key }}", htmlspecialchars($value), $content);
            $content = str_replace("{{$key}}", htmlspecialchars($value), $content);
        }

        // Basic conditional blocks {% if condition %}...{% endif %}
        $content = $this->processConditionals($content, $data);

        // Basic loops {% for item in items %}...{% endfor %}
        $content = $this->processLoops($content, $data);

        // Basic includes {% include 'template.twig' %}
        $content = $this->processIncludes($content);

        return $content;
    }

    private function extractBlocks($content) {
        $blocks = [];
        $pattern = '/\{% block ([^%]+) %\}([\s\S]*?)\{% endblock %\}/';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $blocks[$match[1]] = $match[2];
        }

        return $blocks;
    }

    private function processConditionals($content, $data) {
        $pattern = '/\{% if ([^%]+) %\}([\s\S]*?)\{% endif %\}/';
        return preg_replace_callback($pattern, function($matches) use ($data) {
            $condition = trim($matches[1]);
            $body = $matches[2];

            // Simple variable check
            if (isset($data[$condition]) && $data[$condition]) {
                return $body;
            }
            return '';
        }, $content);
    }

    private function processLoops($content, $data) {
        $pattern = '/\{% for ([^ ]+) in ([^%]+) %\}([\s\S]*?)\{% endfor %\}/';
        return preg_replace_callback($pattern, function($matches) use ($data) {
            $itemVar = trim($matches[1]);
            $arrayVar = trim($matches[2]);
            $body = $matches[3];

            if (!isset($data[$arrayVar]) || !is_array($data[$arrayVar])) {
                return '';
            }

            $result = '';
            foreach ($data[$arrayVar] as $item) {
                $itemContent = str_replace("{{ $itemVar }}", htmlspecialchars($item), $body);
                $itemContent = str_replace("{{$itemVar}}", htmlspecialchars($item), $itemContent);
                $result .= $itemContent;
            }

            return $result;
        }, $content);
    }

    private function processIncludes($content) {
        $pattern = '/\{% include \'([^\']+)\' %\}/';
        return preg_replace_callback($pattern, function($matches) {
            $includeTemplate = $matches[1];
            $includePath = $this->templateDir . '/' . $includeTemplate;

            if (file_exists($includePath)) {
                return file_get_contents($includePath);
            }

            return "<!-- Include not found: $includeTemplate -->";
        }, $content);
    }
}