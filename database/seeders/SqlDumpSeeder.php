<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SqlDumpSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::unprepared(<<<'SQL'
INSERT INTO `guests` (`id`, `name`, `email`, `password`, `google_id`, `phone`, `image`, `created_at`, `updated_at`) VALUES
(43, 'Nathann Films', 'nathannfilms4858120@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '117472329615284407778', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocIteowPNhimEzEm0WyqKRTwUYJrdfIaEUFAJXHswMZvK0Sa0HM=s96-c', '2026-06-22 00:10:31', '2026-06-22 00:10:31'),
(44, 'Viphath Kh', 'nongping227@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '101075844550261720997', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocIq6EmeLuzozTKgjuH9OBUA185ELDJjhZXBEkeIrafpGxMCKJWK=s96-c', '2026-06-22 00:12:12', '2026-06-22 00:12:12'),
(45, 'Ra Ven', 'aaaa087621332@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '108297556897921807937', NULL, 'https://phath-image-hotel.s3.amazonaws.com/guests/G6pXVSmYz6wbfhTrzPMPWeaSlbdoH7uOSJo16RMA.jpg', '2026-06-22 00:20:18', '2026-06-23 02:06:34'),
(46, 'Mrr Phath', 'viphath1234@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '117002731109822576948', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocKgUdb95xXOED06kgpRkT9Mh8A-qXEGxM0HnSfcE594RnzrSxcp=s96-c', '2026-06-22 00:48:08', '2026-06-22 00:48:08'),
(47, 'Phea Vi Phath (ផាង វិផាត់)', 'viphathphang7@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '112130625439613132179', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocJmqYvZTYn7I0zllZzFTg6f-d5ttkgmsbNZt9GJl9V7Zu4-DCTI=s96-c', '2026-06-22 00:53:50', '2026-06-22 00:53:50'),
(48, 'Viphath Phang', 'arterburncarlin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '105160189205619340275', NULL, 'https://phath-image-hotel.s3.amazonaws.com/guests/EFx5SrrHhmrktLV8xEib1z3UI24EG5pNNdmI9PQ9.jpg', '2026-06-22 01:33:16', '2026-06-23 02:06:22'),
(52, 'Viphath Viphath', 'viphath123@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '115589286024684559982', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocJo9bkmCzJSV0gXeGlCKxmKMgw46uktTj964Kk35kAMJHExAQ=s96-c', '2026-06-22 09:48:53', '2026-06-22 09:48:53'),
(53, 'Oo Oo', 'oooo099300823@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '112012244140995385282', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocLmoEZ_21N1wt1Ta0_JJYj5Qs3UoOB6m0kLaQ940q4KqJY7BleN=s96-c', '2026-06-22 22:54:41', '2026-06-22 22:54:41');

INSERT INTO `historys` (`id`, `guest_id`, `room_id`, `reservation_id`, `total_stays`, `status`, `created_at`, `updated_at`) VALUES
(74, 44, 3, 91, 1, 'upcoming', '2026-06-22 00:12:58', '2026-06-22 00:12:58'),
(76, 45, 4, 92, 1, 'upcoming', '2026-06-22 00:21:23', '2026-06-22 00:21:23'),
(78, 46, 6, 93, 1, 'upcoming', '2026-06-22 00:48:41', '2026-06-22 00:48:41'),
(80, 47, 5, 94, 1, 'upcoming', '2026-06-22 00:54:22', '2026-06-22 00:54:22'),
(82, 48, 8, 95, 1, 'upcoming', '2026-06-22 01:33:53', '2026-06-22 01:33:53');

INSERT INTO `hotel_notifications` (`id`, `type`, `title`, `body`, `reservation_id`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'reservation', '📋 New Reservation – Phea Vi Phath (ផាង វិផាត់)', '📋 <b>New Reservation Created</b>\n━━━━━━━━━━━━━━━━━━━━━\n👤 <b>Guest:</b> Phea Vi Phath (ផាង វិផាត់)\n🏨 <b>Room:</b> 04\n📊 <b>Floor:</b> 4th Floor\n🏷️  <b>Type:</b> Suite\n💰 <b>Price per night:</b> 0.10 $\n💵 <b>Total amount:</b> 0.10 $\n📅 <b>Check-in:</b> 2026-06-22\n📅 <b>Check-out:</b> 2026-06-23\n🔄 <b>Status:</b> Pending\n⏰ <b>Created at:</b> 2026-06-22 07:54:05', 94, 1, '2026-06-22 00:54:05', '2026-06-22 00:54:37'),
(2, 'status_update', '🔄 Status Updated – Phea Vi Phath (ផាង វិផាត់) → Confirmed', '✅ <b>Reservation Updated</b>\n━━━━━━━━━━━━━━━━━━━━━\n👤 <b>Guest:</b> Phea Vi Phath (ផាង វិផាត់)\n🏨 <b>Room:</b> 04\n📊 <b>Floor:</b> 4th Floor\n🏷️  <b>Type:</b> Suite\n🆔 <b>Reservation ID:</b> #94\n📈 <b>Status changed:</b> Pending → Confirmed\n⏰ <b>Updated at:</b> 2026-06-22 07:54:23', 94, 1, '2026-06-22 00:54:23', '2026-06-22 00:54:36'),
(3, 'reservation', '📋 New Reservation – Viphath Phang', '📋 <b>New Reservation Created</b>\n━━━━━━━━━━━━━━━━━━━━━\n👤 <b>Guest:</b> Viphath Phang\n🏨 <b>Room:</b> 06\n📊 <b>Floor:</b> 1st Floor\n🏷️  <b>Type:</b> Standard\n💰 <b>Price per night:</b> 0.10 $\n💵 <b>Total amount:</b> 0.10 $\n📅 <b>Check-in:</b> 2026-06-22\n📅 <b>Check-out:</b> 2026-06-23\n🔄 <b>Status:</b> Pending\n⏰ <b>Created at:</b> 2026-06-22 08:33:36', 95, 1, '2026-06-22 01:33:36', '2026-06-22 01:50:36'),
(4, 'status_update', '🔄 Status Updated – Viphath Phang → Confirmed', '✅ <b>Reservation Updated</b>\n━━━━━━━━━━━━━━━━━━━━━\n👤 <b>Guest:</b> Viphath Phang\n🏨 <b>Room:</b> 06\n📊 <b>Floor:</b> 1st Floor\n🏷️  <b>Type:</b> Standard\n🆔 <b>Reservation ID:</b> #95\n📈 <b>Status changed:</b> Pending → Confirmed\n⏰ <b>Updated at:</b> 2026-06-22 08:33:54', 95, 1, '2026-06-22 01:33:54', '2026-06-22 01:50:40'),
(5, 'cancellation', '❌ Reservation Cancelled – Nathann Films', '❌ <b>Reservation Cancelled</b>\n━━━━━━━━━━━━━━━━━━━━━\n👤 <b>Guest:</b> Nathann Films\n🏨 <b>Room:</b> 01\n🆔 <b>Reservation ID:</b> #90\n💵 <b>Total amount:</b> 0.30 $\n⏰ <b>Cancelled at:</b> 2026-06-23 06:42:16', 90, 1, '2026-06-22 23:42:16', '2026-06-22 23:43:47');

INSERT INTO `permissions` (`id`, `name`, `group`, `is_menu_web`, `web_route_key`, `created_at`, `updated_at`) VALUES
(54, 'Dashboard.View', 'dashboard_management', 1, '/dashboard', '2026-01-23 23:27:56', NULL),
(55, 'Reservations.View', 'reservation_management', 1, '/reservations', '2026-01-23 23:28:41', NULL),
(56, 'Rooms.View', 'room_management', 1, '/rooms', '2026-01-23 23:28:55', NULL),
(57, 'Guests.View', 'guest_management', 1, '/guests', '2026-01-23 23:28:07', NULL),
(58, 'Reports.View', 'report_management', 1, '/reports', '2026-01-23 23:28:34', NULL),
(59, 'User.View', 'user_management', 1, '/user', '2026-01-23 23:29:06', NULL),
(60, 'Role.View', 'role_management', 1, '/role', '2026-01-23 23:28:49', NULL),
(61, 'Permission.View', 'permission_management', 1, '/permission', '2026-01-23 23:28:28', NULL),
(62, 'Settings.View', 'settings_management', 1, '/settings', '2026-01-23 23:29:01', NULL),
(63, 'Historys.View', 'history_management', 1, '/historys', '2026-01-23 23:28:11', NULL),
(64, 'Permission_Roles.View', 'permission_roles_management', 1, '/permission_roles', '2026-01-11 16:38:33', NULL),
(65, 'Users_Roles.View', 'users_roles_management', 1, '/users_roles', '2026-01-11 16:53:23', NULL);

INSERT INTO `permission_roles` (`role_id`, `permission_id`) VALUES
(1, 54),
(2, 54),
(4, 54),
(1, 55),
(2, 55),
(4, 55),
(1, 56),
(2, 56),
(1, 57),
(2, 57),
(1, 58),
(2, 58),
(1, 59),
(1, 60),
(1, 61),
(1, 62),
(1, 63),
(1, 64),
(1, 65);

INSERT INTO `profiles` (`id`, `user_id`, `phone`, `address`, `image`, `type`, `created_at`, `updated_at`) VALUES
(1, 3, '0963612852', 'Battambang', 'https://phath-image-hotel.s3.amazonaws.com/profiles/seODeQ4KQBXwkpVz90Mg2GTSqj6l64sQIBqcrZvL.png', 'profiles/jg8fmg59x5lceacsbs3m', '2026-01-08 02:56:30', '2026-06-23 01:10:21'),
(13, 16, NULL, NULL, 'https://phath-image-hotel.s3.amazonaws.com/profiles/C33ukjK6muYsR3BEqC8rMhXYObIQqMF5WQ2v7m6d.jpg', 'profiles/wwntxrfk6bxemlz1xoul', '2026-01-24 00:07:37', '2026-06-23 01:10:31'),
(16, 19, NULL, NULL, 'https://phath-image-hotel.s3.amazonaws.com/profiles/4GWJpahkj54suVBugeseoRwUTzFZyx94vsuHNCPo.jpg', 'profiles/otngxrgvtajw6rvmmyv2', '2026-03-02 17:12:54', '2026-06-23 01:10:03');

INSERT INTO `reservations` (`id`, `guest_id`, `room_id`, `check_in`, `check_out`, `status`, `total`, `created_at`, `updated_at`) VALUES
(90, 43, 1, '2026-06-22', '2026-06-25', 'Cancelled', 0.30, '2026-06-22 00:11:01', '2026-06-22 23:42:15'),
(91, 44, 3, '2026-06-23', '2026-06-24', 'Confirmed', 0.10, '2026-06-22 00:12:30', '2026-06-22 00:12:58'),
(92, 45, 4, '2026-06-22', '2026-06-24', 'Confirmed', 0.20, '2026-06-22 00:21:00', '2026-06-22 00:21:23'),
(93, 46, 6, '2026-06-22', '2026-06-23', 'Confirmed', 0.10, '2026-06-22 00:48:23', '2026-06-22 00:48:41'),
(94, 47, 5, '2026-06-22', '2026-06-23', 'Confirmed', 0.10, '2026-06-22 00:54:04', '2026-06-22 00:54:22'),
(95, 48, 8, '2026-06-22', '2026-06-23', 'Confirmed', 0.10, '2026-06-22 01:33:35', '2026-06-22 01:33:53');

INSERT INTO `roles` (`id`, `name`, `code`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'ADMIN', 'System administrator role', 1, '2026-01-08 00:37:08', '2026-01-09 00:37:39'),
(2, 'Manager', 'MANAGER', 'Department manager role', 1, '2026-01-08 00:37:24', '2026-01-08 00:37:24'),
(4, 'Cashier', 'CASHIER', 'Receives payments, gives change, prints receipts, keeps cash drawer balanced.', 1, '2026-01-09 19:00:52', '2026-01-09 19:00:52');

INSERT INTO `rooms` (`id`, `number`, `type`, `floor`, `capacity`, `price`, `status`, `image`, `created_at`, `updated_at`) VALUES
(1, '01', 'Executive', '1st Floor', 2, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/V0gCLdqzwZJXAllc9pDV7coZYYZGGZaKc4liF0YF.jpg', '2026-01-08 00:41:56', '2026-06-23 02:07:27'),
(3, '02', 'Deluxe', '2nd Floor', 2, 0.10, 'occupied', 'https://phath-image-hotel.s3.amazonaws.com/rooms/JeyLtvi6uUeYBKoWtDhnegFjoDg3WelJyJEYZlw1.jpg', '2026-01-08 00:44:54', '2026-06-23 02:07:43'),
(4, '03', 'Standard', '3rd Floor', 2, 0.10, 'occupied', 'https://phath-image-hotel.s3.amazonaws.com/rooms/naaPOLdg7abov6J44K1azQp7oH40nLfOhojdfZoj.jpg', '2026-01-08 17:53:41', '2026-06-23 02:07:52'),
(5, '04', 'Suite', '4th Floor', 2, 0.10, 'occupied', 'https://phath-image-hotel.s3.amazonaws.com/rooms/GiOyPaVErhUzsPm4TtOAvZMQL1g3QKYob5og2RhR.jpg', '2026-01-08 18:06:04', '2026-06-23 02:08:06'),
(6, '05', 'Family', '5th Floor', 4, 0.10, 'occupied', 'https://phath-image-hotel.s3.amazonaws.com/rooms/ysDduB77kdUbbsBtwOfgF2NBKvbVPapFMc0Z8wfv.jpg', '2026-01-08 18:09:18', '2026-06-23 02:08:18'),
(8, '06', 'Standard', '1st Floor', 2, 0.10, 'occupied', 'https://phath-image-hotel.s3.amazonaws.com/rooms/oxcfzSjoZzzMB9sph4y4mqrBw6lqutNDaSe17GR2.jpg', '2026-01-15 23:10:39', '2026-06-23 02:08:28'),
(9, '07', 'Deluxe', '2nd Floor', 2, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/wvvLWk0PN4F0Dd2Xx0QLire6h0bxLUKMIKVyATGl.jpg', '2026-01-15 23:11:14', '2026-06-23 02:08:40'),
(10, '08', 'Suite', '3rd Floor', 2, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/7OVqV652jWBXstopAjH03G21mlo7Z4eBtwDJzezy.jpg', '2026-01-15 23:11:44', '2026-06-23 02:08:53'),
(11, '09', 'Family', '4th Floor', 4, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/YqXT8gldm3piQ5sH7V9neJNuuozGBQqUuJ5nvfDI.jpg', '2026-01-15 23:12:14', '2026-06-23 02:09:04'),
(12, '10', 'Executive', '5th Floor', 2, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/A0nlZ8frtk7pfekZ3vEdxpO1HW1eAqX7ztcfkXQK.jpg', '2026-01-15 23:12:43', '2026-06-23 02:09:14'),
(13, '11', 'Standard', '1st Floor', 2, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/NIylMJ74HTBj2Xmf8T4MVAAdCutm2lNAnt1T483m.jpg', '2026-01-15 23:28:53', '2026-06-23 02:09:23'),
(14, '12', 'Deluxe', '2nd Floor', 2, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/B4akyLrtVCOOmIv08spP6ElIXo6CJYaz6IJ3gr5S.jpg', '2026-01-15 23:29:18', '2026-06-23 02:09:40'),
(15, '13', 'Suite', '3rd Floor', 2, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/ADYIoyFJlzpt3LSj5poiDdy3R7NrlPQ6xNcOdMT1.jpg', '2026-01-15 23:34:05', '2026-06-23 02:10:00'),
(16, '14', 'Family', '4th Floor', 2, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/uY2yI2MGWHTGUoD0eOmSUB1GobRCAEcGGctfxnW6.jpg', '2026-01-15 23:36:47', '2026-06-23 02:10:11'),
(17, '15', 'Family', '4th Floor', 4, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/foUC8CCLgYLzxr7Pzm6X75NNSJiEub2250U4SC7n.jpg', '2026-01-15 23:52:11', '2026-06-23 02:10:21'),
(18, '16', 'Executive', '5th Floor', 2, 0.10, 'available', 'https://res.cloudinary.com/doe2yx3ao/image/upload/v1772289852/rooms/nwfsst1yglx9fqpl0vpn.jpg', '2026-01-15 23:52:44', '2026-02-28 07:44:13'),
(19, '17', 'Standard', '2nd Floor', 2, 0.10, 'available', 'https://res.cloudinary.com/doe2yx3ao/image/upload/v1772289867/rooms/lccd1vwouiz1z7tb9rd7.jpg', '2026-01-15 23:56:55', '2026-02-28 07:44:27'),
(20, '18', 'Suite', '3rd Floor', 2, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/1di2HAvRF4VQuzbaMoJ1KQlc1OQCodZSSBlCLra3.jpg', '2026-01-15 23:58:56', '2026-06-23 02:22:49'),
(21, '19', 'Family', '4th Floor', 4, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/ho1jGjKtxnN9a0modQYd1fsvXeKpmykRmPuZ5xk2.jpg', '2026-01-15 23:59:25', '2026-06-23 02:23:00'),
(22, '20', 'Executive', '5th Floor', 2, 0.10, 'available', 'https://phath-image-hotel.s3.amazonaws.com/rooms/8bslMfLfo9Y83ueGVhlPjTMb6B0OnNO6avt4Mdro.jpg', '2026-01-15 23:59:48', '2026-06-23 02:23:08');

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'PhathTech', 'phang.viphath@gmail.com', NULL, '$2y$10$Wxhg31UBELljvD8OUm32/erCuHHf.B/jyPfSaP7jc8r4GXJXUnmX6', NULL, '2026-01-08 02:56:30', '2026-01-11 17:18:55'),
(16, 'Vai Sopha', 'vai.sopha@gmail.com', NULL, '$2y$10$ZiOTsTBSHidy3LeSwn/H1OfwsbOTR0NV2JAeZr6nZeEos7Nre0.eS', NULL, '2026-01-24 00:07:37', '2026-01-24 00:07:37'),
(19, 'Cashier', 'Cashier@gmail.com', NULL, '$2y$10$2qwsXM3ktGRfBGNjZU7H6.Ipm3NNEJSYgtwJg05Z8KqACLUgkZZ96', NULL, '2026-03-02 17:12:54', '2026-03-02 17:12:54');

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(3, 1),
(16, 2),
(19, 4);

SQL
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
