<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Yajra\DataTables\EloquentDataTable;

/**
 * This will make default parameter and language for databable
 */
class DataTablesService extends EloquentDataTable
{
    private $rawColumns = [];
    private $filterUrl = false;

    public function addRawColumn($column)
    {
        $columns = Arr::wrap($column);

        foreach ($columns as $col) {
            $this->rawColumns[] = $col;
        }
    }

    public function editFilterColumn($col)
    {
    }

    /**
     * @param $column
     * @param $type
     * @param mixed ...$config
     * @return $this
     */
    public function editColumnAuto($column, $type, $config = [])
    {
        $defaultConfig = [
            'type'           => '',
            'filter_url'     => false,
            'filter_columns' => [],
        ];

        $config += $defaultConfig;

        $this->editColumn($column, function ($item) use ($column, $type, $config) {
            $value = $this->parseFormat($type, $item->$column, $config);
            $value = $this->parseFilter($item, $item->$column, $value, $config);

            return $value;
        });

        $this->addRawColumn($column);

        return $this;
    }

    public function parseFormat($format, $value, $config = [])
    {
        switch ($format) {
            case 'alias':
                $alias = $config['alias'][$value] ?? $value;

                return $alias;
            case 'price':
                return '<span class="pull-right">' . toThousand($value) . '</span>';
            case 'period_month':
                $date = $value instanceof Carbon ? $value : Carbon::parse($value);

                return '<span class="pull-right">' . $date->formatLocalized('%B %Y') . '</span>';
            case 'long_date':
                $date = $value instanceof Carbon ? $value : Carbon::parse($value);

                return '<span class="pull-right">' . $date->formatLocalized('%d %B %Y') . '</span>';
            case 'pill':
                $color = $config['color'][$value] ?? '';
                $alias = $config['alias'][$value] ?? $value;

                return "<span class='badge badge-pill {$color}'>{$alias}</span>";
            default:
                return $value;
        }
    }

    public function setFilterUrl($filterUrl)
    {
        $this->filterUrl = $filterUrl;

        return $this;
    }

    public function make($mDataSupport = true)
    {
        // ddr($this->rawColumns);
        if (isset($this->columnDef['raw'])) {
            $this->columnDef['raw'] = array_merge($this->columnDef['raw'], $this->rawColumns, ['action']);
        } else {
            $this->rawColumns(array_merge($this->rawColumns, ['action']));
        }

        return parent::make();
    }

    private function parseFilter($data, $originalValue, $formattedValue, $config)
    {
        $val = $formattedValue;

        if (count($config['filter_columns']) > 0) {
            $filterColumns = [
                'filtered' => 'true',
            ];

            foreach ($config['filter_columns'] as $key => $item) {
                if (is_string($key)) {
                    $filterColumns[urlencode($key)] = urlencode($data->$item);

                    continue;
                }

                if ($originalValue instanceof Carbon) {
                    $originalValue = $originalValue->toDateString();
                }

                $filterColumns[$item] = urlencode($originalValue);
            }

            $url = qs_url($this->filterUrl, $filterColumns);

            $val = "<a href='$url'>" . $val . '</a>';
        }

        return $val;
    }
}
