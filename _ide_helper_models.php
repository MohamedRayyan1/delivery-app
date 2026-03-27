<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int|null $restaurant_id
 * @property string $image
 * @property string|null $title
 * @property string|null $content
 * @property string $status
 * @property numeric $cost
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\Restaurant|null $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad whereUpdatedAt($value)
 */
	class Ad extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int|null $restaurant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Restaurant|null $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUserId($value)
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cart_id
 * @property int $item_id
 * @property int $quantity
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MenuItem $item
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereUpdatedAt($value)
 */
	class CartItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $discount_type
 * @property numeric $value
 * @property numeric|null $min_order_price
 * @property \Illuminate\Support\Carbon $expiry_date
 * @property int|null $usage_limit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereMinOrderPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUsageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereValue($value)
 */
	class Coupon extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $user_id
 * @property int $points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerProfile wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerProfile whereUserId($value)
 */
	class CustomerProfile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $driver_id
 * @property numeric $offered_delivery_fee
 * @property string|null $required_vehicle_type
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Driver $driver
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest whereOfferedDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest whereRequiredVehicleType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryRequest whereUpdatedAt($value)
 */
	class DeliveryRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property bool $is_online
 * @property string $account_status
 * @property numeric $total_earnings
 * @property string $vehicle_type
 * @property string|null $vehicle_plate_number
 * @property string|null $license_image
 * @property numeric|null $current_lat
 * @property numeric|null $current_lng
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereAccountStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCurrentLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCurrentLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereIsOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereLicenseImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereTotalEarnings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereVehiclePlateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereVehicleType($value)
 */
	class Driver extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gift whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gift wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gift whereUpdatedAt($value)
 */
	class Gift extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Governorate whereUpdatedAt($value)
 */
	class Governorate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $menu_item_id
 * @property string $name
 * @property string $category
 * @property numeric $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MenuItem $menuItem
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemExtra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemExtra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemExtra query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemExtra whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemExtra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemExtra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemExtra whereMenuItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemExtra whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemExtra wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemExtra whereUpdatedAt($value)
 */
	class ItemExtra extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $sub_section_id
 * @property string $name
 * @property string|null $description
 * @property numeric $price
 * @property numeric|null $discount_price
 * @property string|null $image
 * @property bool $is_featured
 * @property bool $is_available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItemExtra> $extras
 * @property-read int|null $extras_count
 * @property-read mixed $final_price
 * @property-read \App\Models\SubMenuSection $subSection
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereSubSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereUpdatedAt($value)
 */
	class MenuItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Restaurant> $restaurants
 * @property-read int|null $restaurants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubMenuSection> $subSections
 * @property-read int|null $sub_sections_count
 * @method static \Database\Factories\MenuSectionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSection whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuSection whereUpdatedAt($value)
 */
	class MenuSection extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $restaurant_id
 * @property int|null $driver_id
 * @property int $address_id
 * @property int|null $coupon_id
 * @property string|null $delivery_confirmation_code
 * @property string $status
 * @property string $payment_method
 * @property string $payment_status
 * @property string|null $transaction_ref
 * @property numeric $subtotal
 * @property numeric $delivery_fee
 * @property numeric $discount_amount
 * @property numeric $grand_total
 * @property numeric $applied_restaurant_commission
 * @property numeric $applied_driver_share
 * @property \Illuminate\Support\Carbon|null $picked_up_at
 * @property \Illuminate\Support\Carbon|null $delivered_at
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserAddress $address
 * @property-read mixed $app_earnings
 * @property-read \App\Models\Driver|null $driver
 * @property-read mixed $driver_earnings
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Restaurant $restaurant
 * @property-read mixed $restaurant_earnings
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAppliedDriverShare($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAppliedRestaurantCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryConfirmationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePickedUpAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTransactionRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $item_id
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MenuItem $item
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $phone
 * @property string $code
 * @property bool $is_used
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereUpdatedAt($value)
 */
	class Otp extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $manager_user_id
 * @property string $name
 * @property string $governorate
 * @property string $city
 * @property string $status
 * @property string|null $logo
 * @property string|null $cover_image
 * @property string|null $description
 * @property numeric $rating
 * @property numeric $delivery_cost
 * @property numeric $min_order_price
 * @property string|null $delivery_time
 * @property bool $is_featured
 * @property numeric $lat
 * @property numeric $lng
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $manager
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuSection> $sections
 * @property-read int|null $sections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubMenuSection> $subMenuSections
 * @property-read int|null $sub_menu_sections_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereDeliveryCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereGovernorate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereManagerUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereMinOrderPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereUpdatedAt($value)
 */
	class Restaurant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property int|null $restaurant_id
 * @property numeric|null $restaurant_rating
 * @property int|null $driver_id
 * @property numeric|null $driver_rating
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Driver|null $driver
 * @property-read \App\Models\Restaurant|null $restaurant
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereDriverRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRestaurantRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUserId($value)
 */
	class Review extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $restaurant_id
 * @property string $name
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Restaurant $restaurant
 * @property-read \App\Models\MenuSection|null $section
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubMenuSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubMenuSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubMenuSection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubMenuSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubMenuSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubMenuSection whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubMenuSection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubMenuSection whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubMenuSection whereUpdatedAt($value)
 */
	class SubMenuSection extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $governorate
 * @property string $phone_number
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportContact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportContact whereGovernorate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportContact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportContact whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportContact wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportContact whereUpdatedAt($value)
 */
	class SupportContact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string $value
 * @property string|null $description
 * @property string $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereValue($value)
 */
	class SystemSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property string $password
 * @property string $role
 * @property string|null $city
 * @property string|null $fcm_token
 * @property bool $is_banned
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserAddress> $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\CustomerProfile|null $customerProfile
 * @property-read \App\Models\Driver|null $driver
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItem> $favoriteItems
 * @property-read int|null $favorite_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Restaurant> $favoriteRestaurants
 * @property-read int|null $favorite_restaurants_count
 * @property-read \App\Models\Restaurant|null $managedRestaurant
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsBanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $label
 * @property string $street
 * @property string|null $details
 * @property string|null $floor
 * @property string|null $phone
 * @property numeric $lat
 * @property numeric $lng
 * @property bool $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAddress whereUserId($value)
 */
	class UserAddress extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int|null $order_id
 * @property string $type
 * @property numeric $amount
 * @property numeric $balance_after
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereBalanceAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalletTransaction whereUserId($value)
 */
	class WalletTransaction extends \Eloquent {}
}

