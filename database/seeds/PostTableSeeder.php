<?php

use App\Post;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    public function run()
    {
        $post = new Post;
        $post->user_id = 2;
        $post->title = "Liste des fonctionnalités";
        $post->body = "Voilà, le site est en développement mais il y a quand même du contenu dessus. Cet article n'est pas prévu pour rester lors de l'ouverture publique !
        
        - Banque:
            - Création/Édition/Délétion de comptes bancaires (commun)
            - Virements bancaires
            - Crédits bancaires
            - Gestion de sa banque
            - Messagerie
            
        - Immobilier:
            - (en cours de restructuration)";
        $post->quill = "test";
        $post->save();
    }
}
