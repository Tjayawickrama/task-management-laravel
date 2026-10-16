<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    // හැදෙන tasks ගණන් කරන්න
    private static int $counter = 0;

    public function definition(): array
    {
        $tasks = [
            'Morning walk in the park'          => 'Take the dog for a 30 minute walk before breakfast.',
            'Buy dog food'                      => 'Pick up the usual dry food and a few treats.',
            'Vet appointment'                   => 'Annual check-up and booster vaccination.',
            'Give flea and tick medicine'       => 'Monthly dose, check the date on the packet.',
            'Bath and grooming'                 => 'Shampoo, brush the coat and trim the nails.',
            'Brush the dog\'s teeth'            => 'Use the pet toothpaste, two minutes is enough.',
            'Clean the water bowl'              => 'Wash and refill with fresh water.',
            'Training session: sit and stay'    => 'Practice for 15 minutes with treats.',
            'Buy new chew toys'                 => 'The old ones are worn out.',
            'Wash the pet bed'                  => 'Machine wash on a gentle cycle.',
            'Book grooming appointment'         => 'Call the salon for next weekend.',
            'Refill the food container'         => null,
            'Evening play time'                 => 'Fetch in the garden.',
            'Check microchip details'           => 'Make sure the contact number is up to date.',
            'Trim the nails'                    => null,
            'Order pet insurance renewal'       => 'Compare plans before renewing.',
            'Deworming tablet'                  => 'Every three months.',
            'Clean the litter box'              => null,
            'Take photos for the pet album'     => 'Park trip this weekend.',
            'Pick up medicine from pharmacy'    => 'Prescription is ready for collection.',
            'Plan a pet birthday celebration'   => 'Prepare a pet-safe treat and a fun activity.',
            'Create a monthly care checklist'   => 'List recurring feeding, grooming, and health care tasks.',
            'Arrange a pet photography session' => 'Plan a short photo session in a familiar, comfortable setting.',
            'Review monthly pet expenses'       => 'Check spending on food, grooming, vet care, and supplies.',
            'Brush the cat\'s fur'              => 'Gently brush the coat to remove loose fur.',
            'Clean the cat litter tray'         => 'Remove waste and maintain a clean litter area.',
            'Replace litter box litter'         => 'Refresh the litter according to the product instructions.',
            'Wash pet food mats'                => 'Clean the mats to remove food spills and dirt.',
            'Clean pet toys'                    => 'Wash suitable toys according to their care instructions.',
            'Wash pet blankets'                 => 'Clean blankets and allow them to dry completely.',
            'Brush the dog\'s coat'             => 'Remove loose fur and check for tangles.',
            'Clean muddy paws'                  => 'Wipe the paws after outdoor activities.',
            'Check grooming supplies'           => 'Make sure brushes, shampoo, and towels are ready.',
            'Book a nail trimming session'      => 'Arrange a professional nail trim if needed.',
            'Clean the pet carrier'             => 'Remove dirt and clean the carrier before its next use.',
            'Disinfect pet feeding area'        => 'Clean the feeding area using pet-safe cleaning products.',
            'Practice leash walking'            => 'Help the dog walk calmly on a leash.',
            'Teach a new command'               => 'Practice one simple command using positive reinforcement.',
            'Practice recall training'          => 'Reward the dog for returning when called.',
            'Play fetch with the dog'           => 'Use a suitable toy for an active play session.',
            'Arrange an indoor play session'    => 'Keep the pet entertained with safe indoor activities.',
            'Practice puzzle toy activities'    => 'Use a suitable puzzle toy for mental stimulation.',
            'Socialize the puppy'               => 'Plan a safe, positive socialization activity.',
            'Practice crate training'           => 'Make the crate comfortable and introduce it gradually.',
            'Explore a new walking route'       => 'Choose a safe route for the next dog walk.',
            'Rotate pet toys'                   => 'Put away some toys and introduce others to keep play interesting.',
            'Check pet identification tag'      => 'Make sure the contact details on the tag are readable.',
        ];

        $petNames = ['Bella', 'Max', 'Luna', 'Charlie', 'Coco', 'Milo', 'Daisy', 'Rocky'];

        $titles = array_keys($tasks);
        $index  = self::$counter++;
        $base   = $titles[$index % count($titles)];
        $round  = intdiv($index, count($titles));

        
        $title = $round === 0
            ? $base
            : $base . ' - ' . $petNames[($round - 1) % count($petNames)];

        return [
            'title'       => $title,
            'description' => $tasks[$base],
            'due_date'    => fake()->optional()->dateTimeBetween('-1 week', '+2 months'),
            'status'      => fake()->randomElement(TaskStatus::cases())->value,
        ];
    }
}