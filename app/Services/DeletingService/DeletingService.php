<?php
namespace App\Services\DeletingService;

use App\Models\Category;
use App\Models\ExtraGroup;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\ShopSubscription;
use App\Models\Stock;
use App\Services\CoreService;
use DB;
use Throwable;

class DeletingService extends CoreService
{
    protected function getModelClass(): string
    {
        return Shop::class;
    }

    /**
     * @param Shop|null $shop
     * @return void
     */
    public function shop(Shop|null $shop): void
    {
        try {
            $shop->bonus()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            DB::table('assign_shop_tags')->where('shop_id', $shop->id)->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $shop->discounts()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $shop->translations()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $shop->workingDays()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $shop->closedDates()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $shop->shopPayments()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $shop->invitations()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $shop->deliveryZone()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $shop->reviews()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

		try {
			$shop->orders()->delete();
		} catch (Throwable $e) {
			$this->error($e);
		}

		try {
			ShopSubscription::where('shop_id', $shop->id)->chunkMap(fn($m) => $m->delete(), 100);
		} catch (Throwable $e) {
			$this->error($e);
		}

		try {
			ShopCategory::where('shop_id', $shop->id)->delete();
		} catch (Throwable $e) {
			$this->error($e);
		}

		try {
			Category::where('shop_id', $shop->id)->delete();
		} catch (Throwable $e) {
			$this->error($e);
		}

		try {
			ExtraGroup::where('shop_id', $shop->id)->delete();
		} catch (Throwable $e) {
			$this->error($e);
		}

        try {
            foreach ($shop->products()->get() as $product) {
                $this->product($product);
            }
        } catch (Throwable $e) {
            $this->error($e);
        }

    }

    /**
     * @param Product|null $product
     * @return void
     */
    public function product(Product|null $product): void
    {
        try {
            $product->stories()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $product->addons()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        foreach ($product->stocks()->get() as $stock) {
            (new DeletingService)->stock($stock);
        }
    }

    /**
     * @param Stock|null $stock
     * @return void
     */
    public function stock(Stock|null $stock): void
    {
        try {
            $stock->cartDetails()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $stock->bonus()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $stock->bonusByShop()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }

        try {
            $stock->addons()->delete();
        } catch (Throwable $e) {
            $this->error($e);
        }
    }

}
