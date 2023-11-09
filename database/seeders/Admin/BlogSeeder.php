<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Blog;
use App\Models\Admin\BlogCategory;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blog_categories = array(
            array('id' => '2','slug' => 'about-us','name' => '{"language":{"en":{"name":"About us"},"es":{"name":null}}}','status' => '1','created_at' => '2023-09-21 09:17:24','updated_at' => '2023-09-21 09:17:24'),
            array('id' => '3','slug' => 'appointments','name' => '{"language":{"en":{"name":"Appointments"},"es":{"name":null}}}','status' => '1','created_at' => '2023-09-21 09:17:48','updated_at' => '2023-09-21 09:17:48'),
            array('id' => '4','slug' => 'service','name' => '{"language":{"en":{"name":"Service"},"es":{"name":null}}}','status' => '1','created_at' => '2023-09-21 09:18:00','updated_at' => '2023-09-21 09:18:00'),
            array('id' => '5','slug' => 'time-schedule','name' => '{"language":{"en":{"name":"Time Schedule"},"es":{"name":null}}}','status' => '1','created_at' => '2023-09-21 09:18:09','updated_at' => '2023-09-21 09:30:10')
        );
        BlogCategory::insert($blog_categories);

        //create blog
        $blogs = array(
            array('category_id' => '2','slug' => 'time-to-meet-our-great-staff-individuals','data' => '{"language":{"en":{"title":"Empowering Entrepreneurs: Success Stories","description":"<p>Empowering Entrepreneurs\\u201d is a special segment dedicated to sharing success stories of salon owners and startups who have thrived with eSalon. Learn from their experiences, gain inspiration, and find valuable insights to fuel your entrepreneurial spirit. We believe that your success story is waiting to be written, and we\\u2019re here to help you create it.<\\/p>","tags":["About Us","Hair Style"]},"es":{"title":"Empoderar a las emprendedoras: historias de \\u00e9xito","description":"<p>Empowering Entrepreneurs\\u201d es un segmento especial dedicado a compartir historias de \\u00e9xito de propietarios de salones y nuevas empresas que han prosperado con eSalon. Aprenda de sus experiencias, insp\\u00edrese y encuentre ideas valiosas para alimentar su esp\\u00edritu emprendedor. Creemos que su historia de \\u00e9xito est\\u00e1 esperando ser escrita y estamos aqu\\u00ed para ayudarlo a crearla.<\\/p>","tags":["historia"]}},"image":"seeder/blog1.webp"}','status' => '1','created_at' => '2023-09-21 09:59:37','updated_at' => '2023-11-09 06:07:04'),
            array('category_id' => '2','slug' => 'what-are-the-secrets-of-the-spa-why-like-us','data' => '{"language":{"en":{"title":"Salon Stories: A Glimpse Behind the Scenes","description":"<p>Salon Stories\\u201d offers a captivating look behind the scenes of the beauty industry. Explore the narratives of our talented professionals, discover the secrets of their artistry, and gain a deeper appreciation for the world of salons and parlours. Join us on a journey into the heart of beauty.<\\/p>","tags":["Color","Treatment"]},"es":{"title":"Historias de sal\\u00f3n: un vistazo detr\\u00e1s de escena","description":"<p>Salon Stories\\u201d ofrece una mirada cautivadora detr\\u00e1s de escena de la industria de la belleza. Explore las narrativas de nuestros talentosos profesionales, descubra los secretos de su arte y obtenga una apreciaci\\u00f3n m\\u00e1s profunda del mundo de los salones y salones. \\u00danase a nosotros en un viaje al coraz\\u00f3n de la belleza.<\\/p>","tags":["coraz\\u00f3n"]}},"image":"seeder/blog2.webp"}','status' => '1','created_at' => '2023-09-21 10:02:23','updated_at' => '2023-11-09 06:05:50'),
            array('category_id' => '3','slug' => 'why-hairstyling-salon-is-the-tranquil-spot','data' => '{"language":{"en":{"title":"Beauty Unveiled: Insights, Tips, and Trends","description":"<p>Dive into the world of beauty and wellness with our blog. \\u201cBeauty Unveiled\\u201d is your go-to source for expert insights, valuable tips, and the latest trends in the industry. Whether you\\u2019re looking for skincare advice, makeup inspiration, or wellness tips, our blog is here to enrich your knowledge and enhance your beauty journey.<\\/p>","tags":["Hair Cut"]},"es":{"title":"Web JournalBeauty revelada: ideas, consejos y tendencias","description":"<p>Sum\\u00e9rgete en el mundo de la belleza y el bienestar con nuestro blog. \\u201cBeauty Unveiled\\u201d es su fuente de referencia para obtener conocimientos de expertos, consejos valiosos y las \\u00faltimas tendencias de la industria. Ya sea que est\\u00e9 buscando consejos para el cuidado de la piel, inspiraci\\u00f3n para el maquillaje o consejos de bienestar, nuestro blog est\\u00e1 aqu\\u00ed para enriquecer sus conocimientos y mejorar su viaje de belleza.<\\/p>","tags":["Sum\\u00e9rgete"]}},"image":"seeder/blog3.webp"}','status' => '1','created_at' => '2023-09-21 10:59:34','updated_at' => '2023-11-09 06:04:20')
          );
        Blog::insert($blogs);
    }
}
