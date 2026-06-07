DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS avatars;

CREATE TABLE posts (
    id          INTEGER PRIMARY KEY NOT NULL,
    title       VARCHAR NOT NULL,
    intro       VARCHAR,
    body        VARCHAR NOT NULL,
    author_id   INTEGER NOT NULL,
    created_at  VARCHAR NOT NULL,
    is_updated  VARCHAR,
    FOREIGN KEY (author_id) REFERENCES  users (id)
);

CREATE TABLE users (
    id          INTEGER PRIMARY KEY NOT NULL,
    first_name  VARCHAR NOT NULL,
    last_name   VARCHAR,
    email       VARCHAR NOT NULL,
    password    VARCHAR NOT NULL,
    role        INTEGER NOT NULL,
    avatar_id   INTEGER,
    FOREIGN KEY (avatar_id) REFERENCES avatars (id)
);

CREATE TABLE avatars (
    id          INTEGER PRIMARY KEY NOT NULL,
    filepath    VARCHAR NOT NULL
);

INSERT INTO users (first_name, last_name, email, password, role) VALUES
('Sarah', 'Connor', 'sarahreviews@gmail.com', '$2y$10$xyzPlaceHolderHash1...', 2),
('Marcus', 'Brody', 'marcusfilms@yahoo.com', '$2y$10$xyzPlaceHolderHash2...', 1),
('Elena', 'Fisher', 'elenascreen@gmail.com', '$2y$10$xyzPlaceHolderHash3...', 1),
('David', 'Fincher', 'b_blanc_fan@outlook.com', '$2y$10$xyzPlaceHolderHash4...', 1),
('Amara', NULL, 'amara_watches@gmail.com', '$2y$10$xyzPlaceHolderHash5...', 0);

INSERT INTO posts (title, intro, body, author_id, created_at, is_updated) VALUES
(
    'Dune: Part Two - A Cinematic Triumph',
    'Denis Villeneuve delivers a sci-fi masterpiece that demands the biggest screen possible.',
    '<p>Rarely does a sequel eclipse its predecessor so completely, but Dune: Part Two manages to pull off the impossible. From the breathtaking vistas of Arrakis to the hauntingly beautiful cinematography, every frame feels like a painting. Timothée Chalamet gives a career-defining performance as Paul Atreides transitions from a reluctant messiah to a terrifying force of nature.</p><p>What truly sets this film apart is its impeccable sound design and Hans Zimmer''s thunderous score, which practically shakes the theater seats. It balances complex political intrigue with visceral, jaw-dropping action sequences that will leave you breathless. If you love science fiction, this isn''t just a must-watch—it''s a historical cinematic event.</p>',
    3, '2026-02-18', 0
),
(
    'The Bear: Season 3 - Still Cooking at High Heat',
    'The chaotic kitchen drama returns with high anxiety, brilliant acting, and deeply emotional stakes.',
    '<p>Yes, Chef! The most stressful show on television is back, and it hasn''t lost a single step. Season 3 dives deeper into the psychological trauma of running a fine-dining establishment while trying to maintain a shred of humanity. Jeremy Allen White continues to sweat charisma and agony in equal measure, leading a flawless ensemble cast.</p><p>While the pacing slows down slightly compared to the frantic rush of Season 2, the character development is richer than ever. It''s a beautiful, exhausting, and ultimately rewarding look at grief, passion, and perfectionism. Just make sure to take a deep breath before hitting play.</p>',
    1, '2026-02-28', 1
),
(
    'Cyberpunk: Edgerunners - A Neon-Soaked Masterpiece',
    'An anime spinoff that completely outshines its source material with pure, unadulterated style.',
    '<p>Trigger has done it again. Cyberpunk: Edgerunners is a hyper-violent, visually spectacular ride through Night City that grabs you by the throat from the opening scene. It follows David, a street kid with nothing to lose, who gets fitted with military-grade cyberware. What follows is a tragic, beautiful, and chaotic spiral into corporate warfare.</p><p>The animation is fluid and bursting with vibrant neon colors, perfectly capturing the dystopian dread of the setting. Beyond the action, the emotional core and the tragic romance will absolutely shatter you by the finale. It''s easily one of the best video game adaptations ever made.</p>',
    2, '2026-02-18', 0
),
(
    'Knives Out 3: Wake Up Dead Man - A Sharp Whodunit',
    'Benoit Blanc returns for another star-studded, delightfully twisty murder mystery.',
    '<p>Rian Johnson has cracked the formula for the modern mystery. In this latest installment, Daniel Craig effortlessly steps back into the linen suits of Detective Benoit Blanc, bringing his signature Southern drawl to a brand new, wildly eccentric ensemble cast. The setup is familiar, but the execution is wildly unpredictable.</p><p>What makes this entry stand out is how sharply it satirizes modern wealth and internet culture without feeling dated. The pacing is snappy, the jokes land beautifully, and the final reveal is both satisfying and incredibly clever. It proves that this franchise still has plenty of gas left in the tank.</p>',
    4, '2026-05-20', 0
),
(
    'Succession - The Greatest Tragedy of the Modern Era',
    'A blistering, hilarious, and devastating look at the toxic dynamics of the ultra-wealthy Roy family.',
    '<p>It''s rare for a show to maintain a flawless track record across its entire run, but Succession manages to stick the landing with devastating precision. The series wraps up its Shakespearean battle for corporate power in a way that feels both inevitable and shocking. Watching the Roy siblings tear each other apart for a crumb of their father''s approval is peak television.</p><p>The dialogue remains as sharp as a razor blade, switching from laugh-out-loud insults to heartbreaking vulnerability in seconds. It''s a masterclass in acting, writing, and directing that we won''t see matched for a very long time.</p>',
    1, '2026-01-10', 1
),
(
    'The Last of Us - Breaking the Video Game Adaptation Curse',
    'Pedro Pascal and Bella Ramsey shine in a brutal, deeply human post-apocalyptic journey.',
    '<p>Forget everything you know about terrible video game adaptations. HBO''s take on the critically acclaimed PlayStation game is a masterpiece of storytelling. It perfectly captures the bleak, terrifying atmosphere of a world ravaged by a fungal pandemic while expanding on the lore in ways that improve upon the original narrative.</p><p>The chemistry between Pedro Pascal''s weary Joel and Bella Ramsey''s fierce Ellie is the beating heart of the show. Episode 3 alone stands as one of the finest hours of television produced in the last decade. It''s violent, emotional, and utterly unmissable.</p>',
    3, '2026-02-28', 0
),
(
    'Spider-Man: Beyond the Spider-Verse - Animation Perfection',
    'The trilogy concludes with a mind-bending, emotionally resonant, and visually stunning finale.',
    '<p>How do you follow up two of the greatest animated movies ever made? By turning the dial up to eleven. Miles Morales faces his toughest challenge yet in a multiversal clash that questions the very nature of heroism. Every single frame of this movie is a testament to the artists who pushed the boundaries of animation.</p><p>The story balances dozens of characters without ever losing sight of Miles'' emotional journey and his relationship with his parents. It is a triumphant, tear-jerking, and adrenaline-pumping conclusion to a legendary trilogy.</p>',
    2, '2026-04-12', 0
),
(
    'Severance: Season 2 - The Corporate Nightmare Deepens',
    'The wait is finally over, and Lumon Industries is weirder and more terrifying than ever.',
    '<p>After that agonizing cliffhanger, Season 2 of Severance arrives with a vengeance. The concept of surgically separating your work memories from your personal memories remains one of the most brilliant premises on television, and the show capitalizes on it beautifully. Adam Scott anchors the series with a brilliant dual performance.</p><p>The show doubles down on its sterile, eerie aesthetic and introduces new mysteries that keep you guessing. It''s a slow-burn psychological thriller that rewards attentive viewers with massive payoffs. Work-life balance has never looked so sinister.</p>',
    5, '2026-05-02', 0
),
(
    'Oppenheimer - A Haunting Look at the Father of the Atomic Bomb',
    'Christopher Nolan delivers a loud, tense, and deeply philosophical biographical masterpiece.',
    '<p>Oppenheimer is less of a standard biopic and more of a three-hour psychological thriller. Cillian Murphy gives a haunting, magnetic performance as J. Robert Oppenheimer, capturing the genius and eventual crushing guilt of the man who altered human history forever. The film moves at a breakneck pace despite being mostly conversations in rooms.</p><p>The Trinity Test sequence is a masterful exercise in tension, utilizing silence just as effectively as explosive sound. It leaves you with a profound sense of dread that lingers long after the credits roll. A monumental achievement in filmmaking.</p>',
    4, '2026-01-25', 1
),
(
    'The White Lotus: Season 3 - Paradise is Still Toxic',
    'Mike White takes his sharp satire to a new continent, mocking the rich in spectacular fashion.',
    '<p> Pack your bags, because class warfare has moved to Thailand. The third season of the anthology series features a brand new group of insufferable, ultra-wealthy elites behaving badly against a gorgeous tropical backdrop. It''s just as uncomfortable, hilarious, and addictive as the previous seasons.</p><p>The show excels at building a slow, simmering tension where you know someone is going to die, but you are too busy laughing at the cringe-inducing social dynamics to care who. The social commentary is bite-sized but sharp, proving Mike White is a master of modern satire.</p>',
    1, '2026-03-22', 0
),
(
    'Everything Everywhere All at Once - A Beautiful Chaos',
    'An existential crisis wrapped inside a martial arts multiverse movie that will make you cry over rocks.',
    '<p>There is absolutely no reason a movie featuring hot dog hands, tax audits, and sentient rocks should work this well, yet it''s one of the most moving films of the decade. Michelle Yeoh is brilliant as an overwhelmed laundromat owner who must tap into alternate realities to save existence. </p><p>Beneath the frantic, spectacular action and absurdist humor lies a deeply moving story about generational trauma, family, and choosing kindness in a meaningless universe. It is a wildly creative breath of fresh air that breaks every rule of Hollywood filmmaking.</p>',
    2, '2026-02-05', 0
),
(
    'Andor - Star Wars Finally Grows Up',
    'A gritty espionage thriller that trades lightsabers for political intrigue and rebellion.',
    '<p>If you thought you were burned out on Star Wars, Andor is the cure. This isn''t a show about space wizards or destiny; it''s a boots-on-the-ground look at the oppressive weight of fascism and the regular people who sacrifice everything to fight it. Diego Luna plays a darker, more desperate version of Cassian Andor.</p><p>Tony Gilroy writes a tightly wound political thriller with some of the best monologues ever put to screen. The prison break arc alone is worth the price of admission. It sets a new high-water mark for what Disney+ franchises can achieve.</p>',
    3, '2026-04-19', 1
),
(
    'Past Lives - The Most Heartbreaking Romance of the Year',
    'A quiet, devastatingly beautiful meditation on fate, love, and the choices that define us.',
    '<p>Past Lives is a movie that lives in the quiet silences between its characters. Celine Song''s directorial debut follows two childhood friends from South Korea who reconnect decades later in New York. It avoids all the classic, melodramatic tropes of a love triangle in favor of something much more mature and painfully realistic.</p><p>Greta Lee and Teo Yoo have an electric, quiet chemistry that anchors the film. It''s a bittersweet examination of "what ifs" and the grief of leaving past versions of yourself behind. Bring tissues; you will need them.</p>',
    5, '2026-03-10', 0
),
(
    'The Boys: Season 5 - The Grim, Gory End Game Begins',
    'The superhero satire gets darker and filthier as Homelander loses his remaining sanity.',
    '<p>The Boys continues to be the ultimate antidote to superhero fatigue. As we inch closer to the final showdown, the stakes have never been higher and the humor has never been more twisted. Antony Starr''s Homelander remains the most terrifying villain on television—a ticking time bomb of pure, unhinged ego.</p><p>While the shock value is still dialed up to a ten, the political satire is razor-sharp. It''s an unapologetic, bloody ride that holds a mirror up to the worst aspects of modern society while delivering top-tier action.</p>',
    4, '2026-06-01', 0
),
(
    'Poor Things - A Wildly Original, Feminist Frankenstein Tale',
    'Emma Stone delivers a mesmerizing, fearless performance in Yorgos Lanthimos'' strangest film yet.',
    '<p>Poor Things is a visual feast that defies categorization. It follows Bella Baxter, a woman brought back to life with the brain of an infant, as she embarks on a surreal journey of sexual and intellectual awakening. Emma Stone is nothing short of magnificent, portraying Bella''s evolution with stunning physical comedy and wit.</p><p>The set designs are a steampunk, Victorian dreamscape, and the script is relentlessly witty. It''s a bold, bizarre, and liberating film that won''t be for everyone, but those who vibe with its eccentric energy will find a masterpiece.</p>',
    2, '2026-01-30', 1
),
(
    'Abbott Elementary - The Comfort Show We All Need',
    'Quinta Brunson''s mockumentary sitcom continues to deliver pure heart and consistent laughs.',
    '<p>In an era of grim, high-budget dramas, Abbott Elementary is a breath of fresh air. This workplace comedy about underfunded teachers in Philadelphia manages to be incredibly funny while shining a light on the real struggles of the education system. The mockumentary format feels fresh thanks to a stellar cast.</p><p>The dynamic between the optimistic Janine and the veteran teacher Barbara provides the show''s emotional anchor. It''s comforting, genuinely witty, and full of characters you can''t help but root for every single week.</p>',
    1, '2026-05-14', 0
),
(
    'Godzilla Minus One - The Best Monster Movie in Decades',
    'A spectacular spectacle that succeeds because it makes you care about the humans on the ground.',
    '<p>Most Godzilla movies treat human characters as boring filler between monster fights. Godzilla Minus One flips the script by delivering a deeply moving post-war drama that would be fantastic even without a giant radioactive lizard. It follows a traumatized kamikaze pilot trying to rebuild his life in a devastated Tokyo.</p><p>When Godzilla does appear, he is terrifying. The visual effects, achieved on a fraction of a Hollywood budget, put major blockbusters to shame. It''s an absolute triumph that returns the franchise to its metaphoric roots.</p>',
    3, '2026-02-12', 0
),
(
    'Fargo: Season 5 - A Brilliant Return to Form',
    'Juno Temple and Jon Hamm face off in a dark, quirky, and gripping Midwestern crime thriller.',
    '<p>After a lukewarm fourth season, Noah Hawley''s anthology crime series returns to its roots with a spectacular fifth installment. Juno Temple is a revelation as a seemingly ordinary Midwestern housewife hiding a dark, survivalist past. Jon Hamm is equally chilling as the constitutional sheriff hunting her down.</p><p>It perfectly balances the Coen brothers'' trademark dark humor, quirky dialogue, and sudden bursts of shocking violence. It''s a tense cat-and-mouse game that keeps you hooked from the chilly opening to the bizarre, poetic finale.</p>',
    4, '2026-04-25', 0
),
(
    'Anatomy of a Fall - A Gripping, Intellectual Legal Drama',
    'Did he fall, or was he pushed? A brilliant courtroom thriller that dissects a collapsing marriage.',
    '<p>This French legal drama is less about solving a crime and more about the impossibility of objective truth. When a man falls to his death from his attic, his wife becomes the prime suspect. The trial that follows acts as an autopsy of their marriage, laid bare for a jury to judge.</p><p>Sandra Hüller gives a mesmerizing, chilly performance that keeps you guessing about her innocence until the very end. The dialogue is sharp, the tension is suffocating, and the movie cleverly forces the audience into the jury box.</p>',
    5, '2026-03-05', 1
),
(
    'Fallout - The Post-Apocalypse Has Never Been This Fun',
    'Amazon successfully captures the retro-futuristic, darkly comedic charm of the video games.',
    '<p>Adapting a massive open-world RPG is no easy feat, but Fallout pulls it off with radioactive style. The show nails the exact tone of the games—a bizarre mixture of 1950s optimism, extreme gore, and cynical corporate satire. Ella Purnell is great as the naive vault dweller experiencing the horrors of the surface for the first time.</p><p>Walton Goggins completely steals the show as The Ghoul, a bounty hunter with a skeletal face and infinite swagger. It''s a wildly entertaining, big-budget sci-fi ride that respects the fans while being completely accessible to newcomers.</p>',
    2, '2026-05-28', 0
);
