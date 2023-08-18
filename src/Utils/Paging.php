<?php

namespace Effectra\Core\Utils;

use Psr\Http\Message\UriInterface;

/**
 * Class Paging
 *
 * Helps with pagination of data and generating navigation links.
 *
 */
class Paging
{
    /**
     * The current page number.
     *
     * @var int
     */
    protected int $page_number = 1;

    /**
     * The number of records to display per page.
     *
     * @var int
     */
    protected int $records_per_page = 5;

    /**
     * The data to be paginated.
     *
     * @var iterable
     */
    protected iterable $data = [];

    /**
     * The base URL used for generating pagination links.
     *
     * @var string
     */
    protected string $uri = "";

    /**
     * Calculates the starting index for the current page's data.
     *
     * @return int
     */
    public function fromPageNumber(): int
    {
        return ($this->records_per_page * $this->page_number) - $this->records_per_page;
    }

    /**
     * Sets the current page number.
     *
     * @param int $number
     * @return $this
     */
    public function setPageNumber(int $number): self
    {
        $this->page_number = $number;
        return $this;
    }

    /**
     * Sets the number of records to display per page.
     *
     * @param int $number
     * @return $this
     */
    public function setRecordsPerPage(int $number): self
    {
        $this->records_per_page = $number;
        return $this;
    }

    /**
     * Sets the data to be paginated.
     *
     * @param iterable $data
     * @return $this
     */
    public function setData(iterable $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Sets the base URL used for generating pagination links.
     *
     * @param string|UriInterface $uri
     * @return $this
     */
    public function setUrl(string|UriInterface $uri): self
    {
        $this->uri = (string) $uri;
        return $this;
    }

    /**
     * Returns the count of the total data items.
     *
     * @return int
     */
    public function countData(): int
    {
        return count($this->data);
    }

    /**
     * Generates pagination data and navigation links.
     *
     * @return array
     */
    public function get(): array
    {
        $page = $this->page_number;
        $total_rows = $this->countData();
        $records_per_page = $this->records_per_page;
        $page_url = $this->uri;
        $paging_arr = array();

        $paging_arr["first"] = array(
            'page' => $page > 1 ? "1" : "",
            'url' => $page > 1 ? "{$page_url}1" : ""
        );

        $total_pages = ceil($total_rows / $records_per_page);

        $range = 2;

        $initial_num = $page - $range;

        $condition_limit_num = ($page + $range)  + 1;

        $paging_arr['pages'] = array();

        $page_count = 0;

        for ($x = $initial_num; $x < $condition_limit_num; $x++) {
            if (($x > 0) && ($x <= $total_pages)) {
                $paging_arr['pages'][$page_count]["page"] = $x;
                $paging_arr['pages'][$page_count]["url"] = "{$page_url}{$x}";
                $paging_arr['pages'][$page_count]["current_page"] = $x == $page ? true : false;

                $page_count++;
            }
        }

        $paging_arr["last"] = array(
            'page' => $total_pages,
            'url' => $page < $total_pages ? "{$page_url}{$total_pages}" : ""
        );

        return [
            'data' => $this->data,
            'pages' => $paging_arr,
            'total' => $this->countData()
        ];
    }
}
