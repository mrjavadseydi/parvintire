<?php

namespace LaraBase\Dashboard;

class Sidebar
{

    private
        $route      = '',
        $active     = '',
        $permission = '';

    private
        $namespaces    = [],
        $baseNamespace = '/',
        $withNamespace = true;

    private
        $title,
        $icon = 'icon-radio-unchecked',
        $onclick;

    private
        $href,
        $isHref,
        $itemHref;

    private
        $badge,
        $badgeType;

    public function resetToDefaults()
    {
        $this->title                = '';
        $this->icon                 = 'icon-radio-unchecked';
        $this->itemHref             = '';
        $this->href                 = '';
        $this->isHref               = false;
        $this->onclick              = '';
        $this->badge                = '';
        $this->badgeType            = '';
        $this->permission           = '';
        $this->withoutNamespace     = false;
        $this->withNamespace              = true;
    }

    public function active()
    {
        if ($this->route == $this->href) {
            $this->active = 'active';
        }
    }

    public function create($options = [], $callback)
    {

        if (isset($options['route']))
            $this->route = $options['route'];

        if (isset($options['namespace']))
            $this->baseNameSpace = '/' . $options['namespace'];

        echo "<ul class='main-ul'>";
        $callback($this);
        echo "</ul>";

        ?>
        <script>
            //$(document).ready(function () {
            //    var parent = $('[href="<?php //echo $this->route?>//"]').parent();
            //    if (!parent.hasClass('main-ul'))
            //    if (parent.length > 0) {
            //       parent.addClass('active');
            //       while (!parent.hasClass('main-ul')) {
            //           parent = parent.parent();
            //           if (parent.hasClass('submenu')) {
            //               parent.slideDown();
            //           }
            //           if (parent.hasClass('treeview')) {
            //               parent.addClass('active');
            //           }
            //       }
            //       $('.main-ul').animate({
            //           scrollTop: parseInt($('[href="/admin/users/roles"]').offset().top)
            //       }, 2000);
            //    }
            //    //scrollToElement('[href="<?php ////echo $this->route?>////"]', 500, '.main-ul')
            //});
        </script>
        <?php

    }

    public function treeview($options, $callback) {

        if (isset($options['namespace']))
                $this->namespaces[] = $options['namespace'];

        $permission = true;

        if (!empty($this->permission))
            if (!\Auth::user()->can($this->permission))
                $permission = false;

        if ($permission) {
            echo "<li class='treeview {$this->active}'>{$this->a(true)}<ul class='submenu'>";
            $callback($this);
            echo "</ul></li>";
        }

        array_pop($this->namespaces);

        $this->resetToDefaults();

    }

    public function a($treeView = false, $namespace = '')
    {

        if ($this->isHref) {
            $this->itemHref = $this->baseNameSpace;

            if ($this->withNamespace)
                if (count($this->namespaces)) {
                    $namespaces = [];
	                foreach ($this->namespaces as $namespace) {
                        if (!empty($namespace))
	                        $namespaces[] = $namespace;
                    }
	                $this->itemHref .= '/' . implode('/', $namespaces);
                }

            if (!empty($this->href))
                $this->itemHref .= '/'. $this->href;
        }

        $a = [
            "<a class='sidebar-item'",
            (!empty($this->itemHref) ? "href={$this->itemHref}" : ""),
            (!empty($this->onclick) ? "onclick={$this->onclick}" : ""),
            "><i class='{$this->icon}'></i><span>{$this->title}</span>",
            ($treeView ? "<i class='icon-toggle icon-keyboard_arrow_left'></i>" : ""),
            (!empty($this->badge) ? "<div class='badge'><span class='{$this->badgeType}'>{$this->badge}</span></div>" : ""),
            "</a>",
        ];

        $this->resetToDefaults();
        return $this->arrayToString($a);

    }

    public function li()
    {
        return "<li class='{$this->active}'>{$this->a()}</li>";
    }

    public function build () {

        $permission = true;

        if (!empty($this->permission))
            if (!\Auth::user()->can($this->permission))
                $permission = false;

        if ($permission)
            echo $this->li();

        $this->resetToDefaults();

    }

    public function title($title, $icon = 'icon-radio-unchecked')
    {
        $this->title = $title;
        $this->icon($icon);
        return $this;
    }

    public function icon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function href($href, $options = null)
    {

        if (isset($options['namespace']))
            $this->withNamespace = $options['namespace'];

        $this->href     = $href;
        $this->isHref   = true;
        return $this;
    }

    public function onclick($onclick)
    {
        $this->onclick = $onclick;
        return $this;
    }

    public function badge($badge, $badgeType = '')
    {
        $this->badge = $badge;
        $this->badgeType($badgeType);
        return $this;
    }

    public function badgeType($badgeType)
    {
        $this->badgeType = $badgeType;
        return $this;
    }

    public function permission($permission)
    {
        $this->permission = $permission;
        return $this;
    }
    
    public function arrayToString($array = [])
    {
        return implode('', $array);
    }

}
