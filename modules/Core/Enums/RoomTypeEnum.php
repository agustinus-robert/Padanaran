<?php

namespace Modules\Core\Enums;

enum RoomTypeEnum: string
{
    case REGULER = 1;
    case DELUXE = 2;
    case PREMIER = 3;
    case EXECUTIVE = 4;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::PREMIER => 'Premier room',
            self::REGULER => 'Standar room',
            self::DELUXE => 'Deluxe room',
            self::EXECUTIVE => 'Executive room',
        };
    }

    /**
     * Get the feature accessor with feature() object
     */
    public function feature(): string
    {
        return match ($this) {
            self::PREMIER => 'Internet access, Free WiFi, Free internet access, LED TV',
            self::REGULER => 'Internet access, Television, Free WiFi, Free internet access',
            self::DELUXE => 'Internet access, Television, High Speed Internet Access, Free WiFi, Free internet access, LED TV',
            self::EXECUTIVE => 'Internet access, Television, High Speed Internet Access, DVD, Free WiFi, Free internet access, LED TV',
        };
    }

    /**
     * Get the feature accessor with feature() object
     */
    public function amenities(): string
    {
        return match ($this) {
            self::PREMIER => 'Air conditioning, Bathrobe, Double glazing windows, Electronic door locks, Electronic smoke detector, Mini Bar, Safety box, Sofa Bed, Balcony, Parking, Iron + board, Work desk, Slippers, Turn down service, Torchlight, Interior doorways',
            self::REGULER => 'Air conditioning, Bathrobe, Double glazing windows, Electronic door locks, Electronic smoke detector, Mini Bar, Safety box, Sofa Bed, Parking, Iron + board, Work desk, Slippers, Turn down service, Torchlight, Interior doorways, Non-smoking rooms available, Smoking rooms available',
            self::DELUXE => 'Air conditioning, Bathrobe, Double glazing windows, Electronic door locks, Electronic smoke detector, Mini Bar, Safety box, Sofa Bed, Parking, Iron + board, Work desk, Slippers, Turn down service, Torchlight, Interior doorways, Non-smoking rooms available, Smoking rooms available',
            self::EXECUTIVE => 'Air conditioning, Alarm clock, Double glazing windows, Electronic door locks, Electronic smoke detector, Mini Bar, Safety box, Sofa Bed, Parking, Iron + board, Tea and coffee maker, Work desk, Slippers, Turn down service, Torchlight, Interior doorways, Non-smoking rooms available',
        };
    }

    /**
     * Get the bathroom accessor with bathroom() object
     */
    public function bathroom(): string
    {
        return match ($this) {
            self::PREMIER => 'Hair dryer, Private Bathroom & WC, Shower, Bathtub, Weighing scale',
            self::REGULER => 'Hair dryer, Private Bathroom & WC, Shower, Bathtub, Weighing scale',
            self::DELUXE => 'Hair dryer, Private Bathroom & WC, Shower, Bathtub, Weighing scale',
            self::EXECUTIVE => 'Hair dryer, Private Bathroom & WC, Shower, Bathtub, Weighing scale',
        };
    }

    /**
     * Get the bed accessor with bed() object
     */
    public function bed(): string
    {
        return match ($this) {
            self::PREMIER => 'Hair dryer, Private Bathroom & WC, Shower, Bathtub, Weighing scale',
            self::REGULER => 'Hair dryer, Private Bathroom & WC, Shower, Bathtub, Weighing scale',
            self::DELUXE => 'Hair dryer, Private Bathroom & WC, Shower, Bathtub, Weighing scale',
            self::EXECUTIVE => 'Hair dryer, Private Bathroom & WC, Shower, Bathtub, Weighing scale',
        };
    }
}
