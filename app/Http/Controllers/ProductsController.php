<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    //商品首页
    public function index(Request $request){
        // 创建一个查询构造器
        $builder = Product::query()->where('on_sale', true);

        /**
         * 判断是否有提交 search 参数，如果有就赋值给 $search 变量
           search 参数用来模糊搜索商品.
         *
         * $builder->where() 里传入一个 function 是为了在查询条件的两边加上 ()，也就是说最终执行的 SQL 语句类似
         *   select * from products where on_sale = 1 and ( title like xxx or description like xxx )。
         * 如果不用这种方式，那生成的 sql 类似:
         *   select * from products where on_sale = 1 and title like xxx or description like xxx
         *   这不符合条件.
         */
        if ($search = $request->input('search', '')) {
            $like = '%'.$search.'%';
            // 模糊搜索商品标题、商品详情、SKU 标题、SKU描述
            $builder->where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('skus', function ($query) use ($like) {
                        $query->where('title', 'like', $like)
                            ->orWhere('description', 'like', $like);
                    });
            });
        }

        // 是否有提交 order 参数，如果有就赋值给 $order 变量
        // order 参数用来控制商品的排序规则
        if ($order = $request->input('order', '')) {
            // 是否是以 _asc 或者 _desc 结尾
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    // 根据传入的排序值来构造排序参数
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }

        $products = $builder->paginate(16);

        return view('products.index', [
            'products' => $products,
            'filters'  => [
                'search' => $search,
                'order'  => $order,
            ],
        ]);
    }

}
