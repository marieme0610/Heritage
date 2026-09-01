# Heritage



**Probleme de conception**
probleme de duplication c'est quel probleme et quel principe de codage le resout quel design patern

deuxieme probleme enfreindre le signle principal responsabilité : creer une instance et enregistrer les donnee
et on enfreint le principe de l'inversion de controlle car on a creer l'instance alors qu'on depend de cette instant on devais le creer pour nous pourque quand on en a besoin on l'utilise directement il ne doit pas connaitre comment l'object et creer 

**Probleme**

    Violation du principe de responsabilité unique (SRP) : Votre service gère à la fois sa propre logique métier ET la gestion de son cycle de vie (instanciation, stockage de l'instance unique).

    Masquage des dépendances : En appelant MonService::getInstance(), le code qui l'utilise masque ses dépendances au lieu de les déclarer ouvertement.

    Couplage fort et impossibilité de tester : Il devient très difficile d'écrire des tests unitaires (avec des Mocks) car l'instance globale conserve son état entre chaque test.

    Incompatibilité avec les paramètres variables : Un Singleton par définition s'instancie une seule fois sans paramètres d'entrée dynamiques (ou avec une configuration globale unique). Si vous devez lui passer des arguments différents selon le contexte, ce n'est plus un Singleton.

**Solution**


**Probleme**
Probleme toutes les classes services doivent etre des singletons 
ce que j'ai fait avec CommandeRepository , Database et CommandeService et en le faisant j'ai vue qu'on faisait une duplication de code dans chaque classe du coup

**Premiere solution**
 j'ai pensais de faire une heritage mais j'ai eu des prombleme pour ecrire un code qui sera utilisable pour toutes les classes mais 

 **Probleme rencontrer pour la solution que je pensais corriger mon probleme**
 
 le probleme en est que dans Database en creeant l'instance le constructeur ne depend de rien donc il ne recoit rien en paramettre contrairement a la classe CommandeService lui qui attent deux paramettre et CommandeRepository qui attend un paramettre donc la si je creer une classe de base la je ne saurais pas combien de paramettre les herité on besoin et il en aura certain qui en n'on pas besoin et donc il seront obligee de l'implementé et la on subira ce qu'on appel l'anti patern.

 **Point de vue**
Dans l'architecture moderne et orientée objet, le Singleton appliqué aux services est considéré comme un Anti-Pattern


Database avantage et inconvenient utilisation comme dependance ou injection le meilleur choix

**Ce que j'ai fait**

La je veux creer une classe de conteneur des injection de dependance je les creer et je les donner le nom de **Container** mais que fait cette classe :
-d'abord j'ai creer un attribut de type array que j'ai initialiser en tableau vide c'est un attribut a private 
-puis j'ai cree une fonction *get(string)* qui prend en paramettre le nom de la classe une chaine entrer dans la fonction je teste si cette nom de classe existe dans mon tableau que j'avais cree si oui je retourne l'instance sinon je cree une nouvelle variable qui stock le resulter du scane de la classe que je fait a travers la classe native *new ReflectionClasse* il prend en paramettre le nom de la classe ReflectionClass permet d'obtenir l'ensemble des information de la classe qu'on lui passe en paramttre elle contient un ensemble d'outils et d'informations statiques sur la structure de la classe.

si c'est fait je teste si cette classe est instanciable avec la methode *isInstantiable()* si cest nom on souleve une exeption 
si elle est instanciable on recupere son constructeur grace a la methode native *getConstructor()* cette methode nous permet de recuperer le constructeur une fois recuperer on teste s'il est null si c'est le cas on retourne l'instance de la classe en le stockant dans une variable object s'il n'est pas null on recupere les paremttre qui se trouve dans cette constructeur avec la methode *getParameters()* puis on creer= un tableau qui vas stocke ces dependances les paramettres d-injection une fois fait on parcours les paramettres recupere dans le constructeur on rcupere les types des paramettres avec la methode *getType()* s'il n'en on pas on souleve une execption si oui a travers le type on recupere le nom de la classe avec la methode *getName()* une fois fait on le stocke dans le tableau qu'on a fait creer avant de parcourir puis le conteneur s'appel lui meme.

-Ensuite on creer une variable la ou on va stocké la variaviable qui contener notre scane de classe avec ReflectionClass pour lui passer les dependances le tableau qu'on avez remplie lors de la parcour des paramettres 
-et pour terminé on ajouter dans notre tableau d'instance creer depuis le debut on y affect l'objet qui contient nos dependance puis on retourne cette object.

**Adaptation avec mes classes CommandeRepository,Database et CommandeService**

sur ces classe j'avais utiliser le singleton maintenant vue que j'ai une classe de conteneur de dependance et en meme temps qui gere les singleton je peut suprimer les singleton de ces classe et utilisé l'injecteur de dependance vue que ce sont des classes qui on une relation de dependency

-j'ai commencer par enlever les singletons du database en suite j'ai enlevais celui de CommandeRepository maintenant labas j'ai rencontrer un petit probleme car mon instance qui contener le container j'ai appele l fonction get qui prend en paramettre le nom de la classe je lui ai donné mais quand j'ai executé j'ai erreur car j'utilise les name space php ne reconnait pas cette nom il connait que le chemin du namespace et j'ai reglé ce probleme en ajoutant **::class** apres le nom de la classe en suite je suis partie vers CommandeService laba aussi j'avais un renconter un probleme car j'avais passer le DTO commme paramettre dans le constructur de cette classe le probleme c'est que le conteneur essaie de traiter le DTO (CommandeDTO) comme un service à injecter, alors qu'un DTO est un simple objet de données et la j'ai enfrein le principe de **l'inversion de controlle** car le CommandeService Un Service ne doit PAS demander un DTO dans son constructeur via le conteneur. Le DTO doit être passé en paramètre de la méthode métier qui en a besoin
