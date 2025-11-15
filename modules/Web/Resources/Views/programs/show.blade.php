@extends('web::layouts.default', [
    'useTransparentNavbar' => true,
])
@section('title', 'Detail Kegiatan | ')

@section('main')
    <div class="relative">
        <div class="-z-20 bg-cover bg-center h-[50vh] absolute top-0 w-full"
            style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/images/rand-3.jpg');">
        </div>
        <div class="container mx-auto space-y-8 mb-16">
            <header class="flex items-center pt-[10vh]">
                <div class="space-y-2 max-w-4xl">
                    <p class="text-gray-200 font-medium">Detail Kegiatan</p>
                    <h1 class="font-bold text-white text-5xl">Velit elit eiusmod veniam in do aliqua magna Elit eiusmod
                        aliquip
                    </h1>
                    <p class="text-gray-200">Officia cupidatat nostrud nulla sint reprehenderit anim dolor do duis ut
                        dolore
                        esse
                        Lorem occaecat ad
                    </p>
                </div>
            </header>
            <div
                class="bg-background dark:bg-muted shadow container mx-auto py-8 rounded-lg prose prose-sm lg:prose-base prose-primary dark:prose-invert max-w-none">
                <div class="flex items-center gap-2">
                    <span>
                        Dari <a href="#" class="text-primary-600 dark:text-primary-500">bangadmin</a></span>
                    <div class="size-2 bg-foreground/15 rounded-full"></div>
                    20 September 2024, 10:29
                </div>
                <p class="lead"> Sit officia non ad culpa pariatur consequat culpa. Enim amet cupidatat labore est velit
                    ea ipsum magna ad. Commodo Lorem ipsum aute quis enim deserunt nisi enim. Duis occaecat incididunt est
                    Lorem proident excepteur qui quis voluptate. Aliquip et aute laborum do excepteur.</p>
                <p>Do nisi ex ad ex. Ex eiusmod id eiusmod laboris sit esse veniam pariatur tempor ipsum tempor. Ullamco qui
                    enim cupidatat non in laboris aliqua. Qui do laboris officia Lorem sit nisi deserunt ad ut ad.</p>
                <p>
                    Eu elit laboris esse labore cupidatat proident aliqua ad laboris. Velit sit aute tempor qui labore ex.
                    Ullamco sint reprehenderit id reprehenderit pariatur pariatur ad amet tempor nostrud. Nisi labore id
                    cillum eiusmod deserunt laboris dolor elit aute in voluptate ut velit. Nulla sint mollit consequat non.
                    Nisi enim fugiat aliquip excepteur consectetur Lorem mollit excepteur.
                </p>
                <figure><img src="https://flowbite.s3.amazonaws.com/typography-plugin/typography-image-1.png"
                        alt="">
                    <figcaption>Digital art by Anonymous</figcaption>
                </figure>
                <h2>Getting started with </h2>
                <p>First of all you need to understand how works. This library is not another framework.
                    Rather, it is a set of components based on Tailwind CSS that you can just copy-paste from the
                    documentation.</p>
                <p>It also includes a JavaScript file that enables interactive components, such as modals, dropdowns,
                    and datepickers which you can optionally include into your project via CDN or NPM.</p>
                <p>You can check out the <a href="https://flowbite.com/docs/getting-started/quickstart/">quickstart
                        guide</a> to explore the elements by including the CDN files into your project. But if you want
                    to build a project with I recommend you to follow the build tools steps so that you can
                    purge and minify the generated CSS.</p>
                <p>You'll also receive a lot of useful application UI, marketing UI, and e-commerce pages that can help
                    you get started with your projects even faster. You can check out this <a
                        href="https://flowbite.com/docs/components/tables/">comparison table</a> to better understand
                    the differences between the open-source and pro version of .</p>
                <h2>When does design come in handy?</h2>
                <p>While it might seem like extra work at a first glance, here are some key moments in which prototyping
                    will come in handy:</p>
                <ol>
                    <li><strong>Usability testing</strong>. Does your user know how to exit out of screens? Can they
                        follow your intended user journey and buy something from the site you’ve designed? By running a
                        usability test, you’ll be able to see how users will interact with your design once it’s live;
                    </li>
                    <li><strong>Involving stakeholders</strong>. Need to check if your GDPR consent boxes are displaying
                        properly? Pass your prototype to your data protection team and they can test it for real;</li>
                    <li><strong>Impressing a client</strong>. Prototypes can help explain or even sell your idea by
                        providing your client with a hands-on experience;</li>
                    <li><strong>Communicating your vision</strong>. By using an interactive medium to preview and test
                        design elements, designers and developers can understand each other — and the project — better.
                    </li>
                </ol>
                <h3>Laying the groundwork for best design</h3>
                <p>Before going digital, you might benefit from scribbling down some ideas in a sketchbook. This way,
                    you can think things through before committing to an actual design project.</p>
                <p>Let's start by including the CSS file inside the <code>head</code> tag of your HTML.</p>
                <h3>Understanding typography</h3>
                <h4>Type properties</h4>
                <p>A typeface is a collection of letters. While each letter is unique, certain shapes are shared across
                    letters. A typeface represents shared patterns across a collection of letters.</p>
                <h4>Baseline</h4>
                <p>A typeface is a collection of letters. While each letter is unique, certain shapes are shared across
                    letters. A typeface represents shared patterns across a collection of letters.</p>
                <h4>Measurement from the baseline</h4>
                <p>A typeface is a collection of letters. While each letter is unique, certain shapes are shared across
                    letters. A typeface represents shared patterns across a collection of letters.</p>
                <h3>Type classification</h3>
                <h4>Serif</h4>
                <p>A serif is a small shape or projection that appears at the beginning or end of a stroke on a letter.
                    Typefaces with serifs are called serif typefaces. Serif fonts are classified as one of the
                    following:</p>
                <h4>Old-Style serifs</h4>
                <ul>
                    <li>Low contrast between thick and thin strokes</li>
                    <li>Diagonal stress in the strokes</li>
                    <li>Slanted serifs on lower-case ascenders</li>
                </ul><img src="https://flowbite.s3.amazonaws.com/typography-plugin/typography-image-2.png" alt="">
                <ol>
                    <li>Low contrast between thick and thin strokes</li>
                    <li>Diagonal stress in the strokes</li>
                    <li>Slanted serifs on lower-case ascenders</li>
                </ol>
                <h3>Laying the best for successful prototyping</h3>
                <p>A serif is a small shape or projection that appears at the beginning:</p>
                <blockquote>
                    <p> is just awesome. It contains tons of predesigned components and pages starting from
                        login screen to complex dashboard. Perfect choice for your next SaaS application.</p>
                </blockquote>
                <h4>Code example</h4>
                <p>A serif is a small shape or projection that appears at the beginning or end of a stroke on a letter.
                    Typefaces with serifs are called serif typefaces. Serif fonts are classified as one of the
                    following:</p>
                <pre><code class="language-html">&lt;dl class="grid grid-cols-2 gap-8 max-w-screen-md text-gray-900 sm:grid-cols-3 dark:text-white"&gt;
&lt;div class="flex flex-col justify-center items-center"&gt;
  &lt;dt class="mb-2 text-3xl font-extrabold"&gt;73M+&lt;/dt&gt;
  &lt;dd class="text-lg font-normal text-gray-500 dark:text-gray-400"&gt;developers&lt;/dd&gt;
&lt;/div&gt;
&lt;div class="flex flex-col justify-center items-center"&gt;
  &lt;dt class="mb-2 text-3xl font-extrabold"&gt;1B+&lt;/dt&gt;
  &lt;dd class="text-lg font-normal text-gray-500 dark:text-gray-400"&gt;contributors&lt;/dd&gt;
&lt;/div&gt;
&lt;div class="flex flex-col justify-center items-center"&gt;
  &lt;dt class="mb-2 text-3xl font-extrabold"&gt;4M+&lt;/dt&gt;
  &lt;dd class="text-lg font-normal text-gray-500 dark:text-gray-400"&gt;organizations&lt;/dd&gt;
&lt;/div&gt;
&lt;/dl&gt;
</code></pre>
                <h4>Table example</h4>
                <p>A serif is a small shape or projection that appears at the beginning or end of a stroke on a letter.
                </p>
                <table>
                    <thead>
                        <tr>
                            <th>Country</th>
                            <th>Date &amp; Time</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>United States</td>
                            <td>April 21, 2021</td>
                            <td><strong>$2,300</strong></td>
                        </tr>
                        <tr>
                            <td>Canada</td>
                            <td>May 31, 2021</td>
                            <td><strong>$300</strong></td>
                        </tr>
                        <tr>
                            <td>United Kingdom</td>
                            <td>June 3, 2021</td>
                            <td><strong>$2,500</strong></td>
                        </tr>
                        <tr>
                            <td>Australia</td>
                            <td>June 23, 2021</td>
                            <td><strong>$3,543</strong></td>
                        </tr>
                        <tr>
                            <td>Germany</td>
                            <td>July 6, 2021</td>
                            <td><strong>$99</strong></td>
                        </tr>
                        <tr>
                            <td>France</td>
                            <td>August 23, 2021</td>
                            <td><strong>$2,540</strong></td>
                        </tr>
                    </tbody>
                </table>
                <h3>Best practices for setting up your prototype</h3>
                <p><strong>Low fidelity or high fidelity?</strong> Fidelity refers to how close a prototype will be to
                    the real deal. If you’re simply preparing a quick visual aid for a presentation, a low-fidelity
                    prototype — like a wireframe with placeholder images and some basic text — would be more than
                    enough. But if you’re going for more intricate usability testing, a high-fidelity prototype — with
                    on-brand colors, fonts and imagery — could help get more pointed results.</p>
                <p><strong>Consider your user</strong>. To create an intuitive user flow, try to think as your user
                    would when interacting with your product. While you can fine-tune this during beta testing,
                    considering your user’s needs and habits early on will save you time by setting you on the right
                    path.</p>
                <p><strong>Start from the inside out</strong>. A nice way to both organize your tasks and create more
                    user-friendly prototypes is by building your prototypes ‘inside out’. Start by focusing on what will
                    be important to your user, like a Buy now button or an image gallery, and list each element by order
                    of priority. This way, you’ll be able to create a prototype that puts your users’ needs at the heart
                    of your design.</p>
                <p>And there you have it! Everything you need to design and share prototypes — right in Figma.
                </p>
            </div>
        </div>
        <aside aria-label="Related articles" class="py-8 lg:py-24 bg-muted">
            <div class="px-4 mx-auto max-w-screen-xl">
                <h2 class="mb-8 text-2xl font-bold ">Kegiatan Terkait</h2>
                <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach (array_slice(config('modules.web.MOCK_events'), 0, 4) as $article)
                        <article class="max-w-xs">
                            <a href="{{ route('web::events.show', $article['id']) }}">
                                <img src="{{ $article['imageUrl'] }}" class="mb-5 rounded-lg" alt="Image 1">
                            </a>
                            <p class="text-xs mb-1 text-muted-foreground">Sep 20, 2024</p>
                            <h2 class="mb-2 text-xl font-bold leading-tight ">
                                <a href="{{ route('web::events.show', $article['id']) }}">
                                    {{ $article['title'] }}
                                </a>
                            </h2>
                            <p class="mb-4 text-gray-500 dark:text-gray-400 line-clamp-3">
                                {{ $article['description'] }}
                            </p>
                            <a href="{{ route('web::events.show', $article['id']) }}"
                                class="inline-flex items-center font-medium underline underline-offset-4 text-primary-600 dark:text-primary-500 hover:no-underline">
                                Baca selengkapnya
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
@endsection
