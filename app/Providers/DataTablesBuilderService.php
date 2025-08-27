<?php

namespace App\Providers;

use Yajra\DataTables\Html\Builder;

/**
 * This will make default parameter and language for databable
 */
class DataTablesBuilderService extends Builder
{
    /**
     * Boiler for raw data to datatable data
     *
     * @param array $nameTitle
     * @return array
     */
    public static function setColumnData(array $nameTitle)
    {
        $cooked = [];
        foreach ($nameTitle as $name => $title) {
            $cooked[] = [
                'name'  => $name,
                'data'  => $name,
                'title' => $title,
            ];
        }

        return $cooked;
    }

    public function setColumns(array $columns)
    {
        foreach ($columns as $key => $column) {
            // if using shorthand
            if (! is_array($column)) {
                $column = [
                    'name'  => $key,
                    'title' => $column,
                ];
            }

            // if has explicitly keyed row
            if (! is_numeric($key)) {
                $column['data'] = $key;
            }

            $this->addColumn([
                'name'       => $column['name'] ?? $key,
                'data'       => $column['data'] ?? $column['name'],
                'title'      => $column['title'],
                'className'  => $column['className'] ?? null,
                'searchable' => $column['searchable'] ?? true,
                'orderable'  => $column['orderable'] ?? true,
            ]);
        }

        return $this;
    }

    /**
     * Like the setColumnData but with iterative addColumn
     *
     * @param array $nameTitle
     * @return $this
     * @deprecated since using setColumns
     */
    public function setColumnDataIterative(array $nameTitle)
    {
        foreach ($nameTitle as $name => $title) {
            if (is_array($title)) {
                $this->addColumn([
                    'name'       => $name,
                    'data'       => $name,
                    'title'      => $title[0],
                    'searchable' => $title[1] ?? true,
                    'orderable'  => $title[2] ?? true,
                ]);
            } else {
                $this->addColumn([
                    'name'  => $name,
                    'data'  => $name,
                    'title' => $title,
                ]);
            }
        }

        return $this;
    }

    /**
     * Wrapper for ordering
     *
     * @param        $column
     * @param string $sort
     * @return $this
     */
    public function setOrder($column, $sort = 'desc')
    {
        $attr = ['order' => [$column, $sort]];

        $this->parameters($attr);

        return $this;
    }

    /**
     * Add default parameters
     *
     * @todo Still must be invoked manually
     * @param  array $attributes default attributes
     * @return CustomBuilder Builder instance
     */
    public function parameters(array $attributes = [])
    {
        // default parameters
        $default = [
            'lengthChange' => false,
            'pagingType'   => 'input',
            'language'     => [
                'sProcessing'   => 'Sedang memproses...',
                'sLengthMenu'   => 'Tampilkan _MENU_ entri',
                'sZeroRecords'  => 'Tidak ditemukan data yang sesuai',
                'sInfo'         => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri',
                'sInfoEmpty'    => 'Menampilkan 0 sampai 0 dari 0 entri',
                'sInfoFiltered' => '(disaring dari _MAX_ entri keseluruhan)',
                'sInfoPostFix'  => '',
                'sUrl'          => '',
                'oPaginate'     =>
                    [
                        'sFirst'    => '<button class="btn btn-primary" style="margin: 5px; font-size: 20px; padding: 2px 11px;">&laquo;</button>',
                        'sPrevious' => '<button class="btn btn-primary" style="margin: 5px; font-size: 20px; padding: 2px 11px;">&lsaquo;</button>',
                        'sNext'     => '<button class="btn btn-primary" style="margin: 5px; font-size: 20px; padding: 2px 11px;">&rsaquo;</button>',
                        'sLast'     => '<button class="btn btn-primary" style="margin: 5px; font-size: 20px; padding: 2px 11px;">&raquo;</button>',
                    ],
            ],
        ];

        // if there's custom attributes passed to ths function, merge with that
        $this->attributes = array_merge_recursive($this->attributes, $default, $attributes);

        return $this;
    }

    /**
     * Wrapper for url
     *
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $ajaxUrl = ['url' => $url];

        $this->ajax($ajaxUrl);

        return $this;
    }

    /**
     * Add default ajax
     *
     * @param string $attributes array of ajax attribtues
     * @return \Yajra\DataTables\Html\Builder             Builder instance
     */
    public function ajax($attributes = '')
    {
        $default = [
            'type'    => 'POST',
            'headers' => [
                'X-CSRF-TOKEN' => csrf_token(),
            ],
        ];

        // if there's custom attributes passed to ths function, merge with that
        $this->ajax = array_merge($default, $attributes);

        return $this;
    }

    /**
     * Wrapper for index
     *
     * @param string $title
     * @return $this
     */
    public function withIndex($title = 'No')
    {
        $this->addIndex(['title' => $title]);

        return $this;
    }

    /**
     * Wrapper for action
     *
     * @param string $title
     * @return $this
     */
    public function withAction($title = 'Aksi')
    {
        $this->addAction([
            'title'     => $title,
            'className' => 'text-center nowrap',
        ]);

        return $this;
    }
}