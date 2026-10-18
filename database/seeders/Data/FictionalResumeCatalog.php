<?php

namespace Database\Seeders\Data;

class FictionalResumeCatalog
{
    /**
     * @return list<array{
     *     code: string,
     *     job_slug: string,
     *     name: string,
     *     email: string,
     *     phone: string,
     *     paragraphs: list<string>
     * }>
     */
    public static function baseProfiles(): array
    {
        return [
            [
                'code' => 'CV-B01',
                'job_slug' => 'back-end-php-laravel-developer',
                'name' => 'Camila Duarte',
                'email' => 'camila.duarte@example.com',
                'phone' => '+55 (11) 98812-4471',
                'paragraphs' => [
                    'Camila Duarte',
                    'Back-end Developer',
                    'Sao Paulo, Brazil | camila.duarte@example.com | +55 (11) 98812-4471',
                    'Professional Summary',
                    'Back-end developer with three years of professional experience building and maintaining web applications with PHP and the Laravel framework. Comfortable owning features from database design to API delivery.',
                    'Professional Experience',
                    'Back-end Developer | Acme Commerce | 2022 - Present',
                    'Developed and maintained Laravel 11 applications handling order processing and inventory synchronization for an e-commerce operation.',
                    'Built RESTful APIs consumed by web and mobile clients, documenting endpoints and versioning contracts.',
                    'Designed relational schemas in MySQL, wrote complex queries and optimized slow reports.',
                    'Introduced automated feature tests with PHPUnit for critical checkout flows.',
                    'Junior PHP Developer | Bright Web Studio | 2021 - 2022',
                    'Maintained PHP websites and internal tools under senior guidance.',
                    'Technical Skills',
                    'PHP, Laravel, MySQL, REST APIs, Git, PHPUnit, Linux basics',
                    'Education',
                    'B.Sc. Computer Science - State University of Sao Paulo - 2021',
                ],
            ],
            [
                'code' => 'CV-B02',
                'job_slug' => 'back-end-php-laravel-developer',
                'name' => 'Rodrigo Menezes',
                'email' => 'rodrigo.menezes@example.com',
                'phone' => '+55 (21) 99145-2280',
                'paragraphs' => [
                    'Rodrigo Menezes',
                    'PHP Developer',
                    'Rio de Janeiro, Brazil | rodrigo.menezes@example.com | +55 (21) 99145-2280',
                    'Professional Summary',
                    'PHP developer with four years of experience delivering enterprise features on the Symfony framework for financial systems.',
                    'Professional Experience',
                    'PHP Developer | Financia Sistemas | 2021 - Present',
                    'Delivered billing modules using Symfony components, Doctrine ORM and Twig templates.',
                    'Designed PostgreSQL schemas, wrote stored procedures and optimized reporting queries.',
                    'Consumed and exposed REST integrations with partner banks and payment providers.',
                    'Software Developer | Delta Sistemas | 2020 - 2021',
                    'Maintained legacy PHP applications and improved database performance.',
                    'Technical Skills',
                    'PHP, Symfony, Doctrine, PostgreSQL, MySQL, REST APIs, Docker',
                    'Education',
                    'B.Sc. Information Systems - Federal University of Rio de Janeiro - 2020',
                ],
            ],
            [
                'code' => 'CV-F01',
                'job_slug' => 'front-end-react-developer',
                'name' => 'Beatriz Almeida',
                'email' => 'beatriz.almeida@example.com',
                'phone' => '+55 (31) 98330-7712',
                'paragraphs' => [
                    'Beatriz Almeida',
                    'Front-end Developer',
                    'Belo Horizonte, Brazil | beatriz.almeida@example.com | +55 (31) 98330-7712',
                    'Professional Summary',
                    'Front-end developer with two years of professional experience building responsive interfaces with React and TypeScript.',
                    'Professional Experience',
                    'Front-end Developer | Nuvem Analytics | 2023 - Present',
                    'Developed single-page applications with React, TypeScript and modern state management.',
                    'Integrated REST APIs with axios, handled loading and error states and improved accessibility of key flows.',
                    'Collaborated in a Git/GitHub workflow with pull requests, code reviews and continuous integration.',
                    'Junior Web Developer | Studio Criativo MG | 2022 - 2023',
                    'Built marketing websites with JavaScript and modern CSS.',
                    'Technical Skills',
                    'React, TypeScript, JavaScript, HTML, CSS, REST APIs, Git, GitHub',
                    'Education',
                    'B.Sc. Computer Science - Federal University of Minas Gerais - 2022',
                ],
            ],
            [
                'code' => 'CV-F02',
                'job_slug' => 'front-end-react-developer',
                'name' => 'Thiago Ferreira',
                'email' => 'thiago.ferreira@example.com',
                'phone' => '+55 (41) 99772-3318',
                'paragraphs' => [
                    'Thiago Ferreira',
                    'Angular Developer',
                    'Curitiba, Brazil | thiago.ferreira@example.com | +55 (41) 99772-3318',
                    'Professional Summary',
                    'Front-end developer with two years of experience building enterprise dashboards with Angular and JavaScript.',
                    'Professional Experience',
                    'Front-end Developer | Paraná Sistemas | 2023 - Present',
                    'Built and maintained Angular modules for internal administration dashboards.',
                    'Consumed REST endpoints and implemented reactive forms with validation.',
                    'Used Git for version control in a team workflow with shared feature branches.',
                    'Technical Skills',
                    'Angular, JavaScript, HTML, CSS, REST APIs, Git',
                    'Education',
                    'Technology Degree in Web Development - Federal University of Parana - 2023',
                ],
            ],
            [
                'code' => 'CV-D01',
                'job_slug' => 'junior-data-analyst',
                'name' => 'Marina Costa',
                'email' => 'marina.costa@example.com',
                'phone' => '+55 (51) 98441-9903',
                'paragraphs' => [
                    'Marina Costa',
                    'Data Analyst',
                    'Porto Alegre, Brazil | marina.costa@example.com | +55 (51) 98441-9903',
                    'Professional Summary',
                    'Data analyst with two years of experience transforming operational data into actionable reports for commercial teams.',
                    'Professional Experience',
                    'Data Analyst | Retail Insights Brasil | 2023 - Present',
                    'Performed data analysis on sales and logistics datasets using SQL and Python, including Pandas for cleaning and aggregation.',
                    'Built interactive Power BI dashboards adopted in weekly commercial reviews.',
                    'Documented metrics definitions and validated data quality with stakeholders.',
                    'Junior Analyst | Contabil Mais | 2022 - 2023',
                    'Prepared recurring financial reports and automated spreadsheet workflows.',
                    'Technical Skills',
                    'SQL, Python, Pandas, Power BI, Excel, Data Visualization',
                    'Education',
                    'B.Sc. Statistics - Federal University of Rio Grande do Sul - 2022',
                ],
            ],
            [
                'code' => 'CV-D02',
                'job_slug' => 'junior-data-analyst',
                'name' => 'Felipe Andrade',
                'email' => 'felipe.andrade@example.com',
                'phone' => '+55 (71) 98220-6654',
                'paragraphs' => [
                    'Felipe Andrade',
                    'Junior Analyst',
                    'Salvador, Brazil | felipe.andrade@example.com | +55 (71) 98220-6654',
                    'Professional Summary',
                    'Junior professional with one year of experience supporting commercial teams with spreadsheets and database reports.',
                    'Professional Experience',
                    'Administrative Analyst | Comercial Bahia | 2024 - Present',
                    'Prepared weekly sales spreadsheets in Excel and extracted data from the corporate SQL database for internal reports.',
                    'Supported managers with formatted summaries and data entry quality control.',
                    'Education',
                    'Associate Degree in Business Administration - State University of Bahia - 2023',
                    'Technical Skills',
                    'Excel, SQL basics, Microsoft Office, data entry',
                ],
            ],
        ];
    }

    /**
     * @return list<array{
     *     code: string,
     *     job_slug: string,
     *     name: string,
     *     email: string,
     *     phone: string,
     *     paragraphs: list<string>
     * }>
     */
    public static function expandedProfiles(): array
    {
        $profiles = [];

        foreach (self::tracks() as $jobSlug => $jobTracks) {
            $index = 1;

            foreach ($jobTracks as $track) {
                foreach (['A', 'B'] as $variant) {
                    $identity = self::identities()[$jobSlug][array_search($track, $jobTracks, true) * 2 + ($variant === 'A' ? 0 : 1)] ?? null;

                    if ($identity === null) {
                        continue;
                    }

                    $profiles[] = [
                        'code' => self::prefix($jobSlug).'-'.str_pad((string) $index, 2, '0', STR_PAD_LEFT).$variant,
                        'job_slug' => $jobSlug,
                        'name' => $identity['name'],
                        'email' => $identity['email'],
                        'phone' => $identity['phone'],
                        'paragraphs' => array_merge(
                            [
                                $identity['name'],
                                $track['title'],
                                $identity['city'].' | '.$identity['email'].' | '.$identity['phone'],
                                'Professional Summary',
                                $track['summary'],
                                'Professional Experience',
                            ],
                            $track['experience'],
                            [
                                'Technical Skills',
                                $track['skills'],
                                'Education',
                                $track['education'],
                            ],
                        ),
                    ];

                    $index++;
                }
            }
        }

        return $profiles;
    }

    /** @return array<string, list<array<string, mixed>>> */
    private static function tracks(): array
    {
        return [
            'back-end-php-laravel-developer' => [
                [
                    'title' => 'Full-stack PHP Developer',
                    'summary' => 'PHP developer with four years of experience delivering Laravel applications and MySQL-backed services.',
                    'experience' => [
                        'Full-stack Developer | Orion Sistemas | 2021 - Present',
                        'Delivered client projects with Laravel, Blade templates and MySQL in an agile team.',
                        'Maintained REST endpoints and background jobs with queues for report generation.',
                        'PHP Developer | Code Horizonte | 2020 - 2021',
                        'Implemented internal tools in PHP with Laravel and PostgreSQL.',
                    ],
                    'skills' => 'PHP, Laravel, MySQL, PostgreSQL, REST APIs, Git, Docker',
                    'education' => 'B.Sc. Computer Science - Catholic University of Brasilia - 2020',
                ],
                [
                    'title' => 'PHP Developer',
                    'summary' => 'Developer focused on back-end services with strong SQL practice and lightweight PHP frameworks.',
                    'experience' => [
                        'PHP Developer | Web Prime Ltda | 2022 - Present',
                        'Built back-end features in plain PHP and CodeIgniter with MySQL databases.',
                        'Collaborated on REST integrations with logistics partners.',
                        'Helped migrate legacy modules toward more structured service layers.',
                    ],
                    'skills' => 'PHP, CodeIgniter, MySQL, REST APIs, Git, Linux',
                    'education' => 'Associate Degree in Systems Analysis - SENAC - 2021',
                ],
                [
                    'title' => 'Back-end Developer',
                    'summary' => 'Back-end developer with solid SQL and API experience and limited exposure to Laravel in personal projects only.',
                    'experience' => [
                        'Back-end Developer | Sampa Digital | 2023 - Present',
                        'Developed REST services in Node.js and maintained MySQL schemas for a retail platform.',
                        'Studied Laravel in personal projects, building a small blog with migrations and factories.',
                        'Support Engineer | Conecta TI | 2022 - 2023',
                        'Resolved database incidents and wrote SQL scripts for customer support.',
                    ],
                    'skills' => 'Node.js, JavaScript, MySQL, SQL, REST APIs, Git, Laravel (personal projects)',
                    'education' => 'B.Sc. Information Systems - University of Sao Paulo - 2022',
                ],
                [
                    'title' => 'Software Developer',
                    'summary' => 'Software developer with five years of experience in Java back-end systems and relational databases.',
                    'experience' => [
                        'Software Developer | Banco Virtual | 2020 - Present',
                        'Developed high-volume services in Java with Spring and PostgreSQL.',
                        'Designed REST integrations and wrote performance-tuned SQL queries.',
                        'Participated in code reviews and incident response rotations.',
                    ],
                    'skills' => 'Java, Spring, PostgreSQL, REST APIs, Docker, Git',
                    'education' => 'B.Sc. Computer Science - Federal University of Bahia - 2019',
                ],
                [
                    'title' => 'Junior PHP Developer',
                    'summary' => 'Junior developer with internship experience in PHP maintenance tasks and MySQL reporting queries.',
                    'experience' => [
                        'Junior PHP Developer | Agil Web | 2024 - Present',
                        'Fixes bugs in legacy PHP pages and adds small features with guidance.',
                        'Writes MySQL queries for operational spreadsheets and simple REST endpoints.',
                        'Intern | Agil Web | 2023 - 2024',
                        'Supported the team with documentation and test data preparation.',
                    ],
                    'skills' => 'PHP, MySQL, HTML, CSS, Git basics',
                    'education' => 'B.Sc. Computer Science - Private University of Curitiba - 2025 (in progress)',
                ],
                [
                    'title' => 'Front-end Developer',
                    'summary' => 'Front-end developer with three years of React experience and only introductory contact with server-side development.',
                    'experience' => [
                        'Front-end Developer | Interface Labs | 2022 - Present',
                        'Builds React interfaces and consumes REST APIs created by the back-end team.',
                        'Completed an online course covering PHP fundamentals and Laravel basics.',
                    ],
                    'skills' => 'React, JavaScript, TypeScript, CSS, REST APIs, Git, PHP (basic)',
                    'education' => 'B.Sc. Design - University Nove de Julho - 2022',
                ],
            ],
            'front-end-react-developer' => [
                [
                    'title' => 'React Developer',
                    'summary' => 'React developer with three years of experience in TypeScript applications and design system adoption.',
                    'experience' => [
                        'React Developer | Fluxo Tecnologia | 2022 - Present',
                        'Developed complex forms and dashboards with React, TypeScript and React Query.',
                        'Consumed REST APIs and improved performance with memoization and code splitting.',
                        'Front-end Developer | Web Ágil | 2021 - 2022',
                        'Delivered responsive interfaces with JavaScript and component libraries.',
                    ],
                    'skills' => 'React, TypeScript, JavaScript, REST APIs, Git, GitHub, CSS',
                    'education' => 'B.Sc. Computer Science - Federal University of Santa Catarina - 2021',
                ],
                [
                    'title' => 'Front-end Developer',
                    'summary' => 'Front-end developer with two years of JavaScript SPA experience, primarily with Vue and some React exposure in training.',
                    'experience' => [
                        'Front-end Developer | Vista Sistemas | 2023 - Present',
                        'Builds single-page applications with Vue, Vuex and REST integrations.',
                        'Completed team training on React and rebuilt a personal portfolio with React hooks.',
                        'Used Git and GitHub daily in a pull request workflow.',
                    ],
                    'skills' => 'Vue, JavaScript, HTML, CSS, REST APIs, Git, GitHub, React (training)',
                    'education' => 'Technology Degree in Web Development - Federal Institute of Sao Paulo - 2023',
                ],
                [
                    'title' => 'UI Developer',
                    'summary' => 'UI developer with strong CSS and accessibility skills and JavaScript experience without a component framework.',
                    'experience' => [
                        'UI Developer | Pixel Studio | 2023 - Present',
                        'Implements pixel-perfect responsive layouts from Figma designs with HTML, CSS and vanilla JavaScript.',
                        'Improves accessibility attributes and browser compatibility across products.',
                        'Version control with Git and GitHub pages deployments.',
                    ],
                    'skills' => 'HTML, CSS, JavaScript, Accessibility, Figma, Git, GitHub',
                    'education' => 'Associate Degree in Graphic Design - Senac SP - 2022',
                ],
                [
                    'title' => 'Full-stack Developer',
                    'summary' => 'Full-stack developer with Laravel back-end focus and intermediate React knowledge from internal tooling projects.',
                    'experience' => [
                        'Full-stack Developer | Acme Systems | 2021 - Present',
                        'Maintains Laravel APIs and builds internal admin interfaces with React and JavaScript.',
                        'Integrates REST endpoints and handles authentication flows.',
                        'Participates in Git-based team workflows with code reviews.',
                    ],
                    'skills' => 'PHP, Laravel, React, JavaScript, MySQL, REST APIs, Git',
                    'education' => 'B.Sc. Information Systems - University of Passo Fundo - 2021',
                ],
                [
                    'title' => 'Junior Front-end Developer',
                    'summary' => 'Junior developer completing first professional year with HTML, CSS and JavaScript foundations.',
                    'experience' => [
                        'Junior Front-end Developer | Start Web | 2025 - Present',
                        'Builds marketing pages and simple interactive components with JavaScript.',
                        'Started learning React through official documentation and small exercises.',
                        'Uses Git for version control on the team repository.',
                    ],
                    'skills' => 'HTML, CSS, JavaScript, Git, React (learning)',
                    'education' => 'B.Sc. Computer Science - University of Caxias do Sul - in progress',
                ],
                [
                    'title' => 'Mobile Developer',
                    'summary' => 'Mobile developer with Flutter experience and general programming fundamentals, without dedicated React or web SPA background.',
                    'experience' => [
                        'Mobile Developer | App Center | 2023 - Present',
                        'Develops cross-platform mobile applications with Flutter and Dart.',
                        'Consumes REST APIs and collaborates through Git and GitHub pull requests.',
                        'Basic knowledge of JavaScript from previous academic projects.',
                    ],
                    'skills' => 'Flutter, Dart, REST APIs, Git, GitHub, JavaScript (basic)',
                    'education' => 'B.Sc. Computer Science - Federal University of Amazonas - 2023',
                ],
            ],
            'junior-data-analyst' => [
                [
                    'title' => 'Data Analyst',
                    'summary' => 'Analyst with two years of SQL reporting and Python automation experience for commercial metrics.',
                    'experience' => [
                        'Data Analyst | Métrica Viva | 2023 - Present',
                        'Writes complex SQL queries and builds Python scripts with Pandas to automate weekly reporting.',
                        'Presents insights through dashboards and written summaries for non-technical stakeholders.',
                        'Junior Analyst | Dados & Cia | 2022 - 2023',
                        'Prepared recurring Excel reports and cleaned operational datasets.',
                    ],
                    'skills' => 'SQL, Python, Pandas, Excel, Power BI, Data Visualization',
                    'education' => 'B.Sc. Statistics - State University of Londrina - 2022',
                ],
                [
                    'title' => 'Business Analyst',
                    'summary' => 'Business analyst with two years of process analysis experience using Excel and BI dashboards built in Power BI.',
                    'experience' => [
                        'Business Analyst | Gestão Integrada | 2023 - Present',
                        'Maps business processes, gathers reporting requirements and builds Power BI dashboards.',
                        'Queries SQL databases for operational indicators and validates results with area owners.',
                        'No routine use of Python or Pandas in current responsibilities.',
                    ],
                    'skills' => 'Excel, Power BI, SQL, Requirements Gathering, Process Modeling',
                    'education' => 'B.Sc. Business Administration - University of Fortaleza - 2022',
                ],
                [
                    'title' => 'Junior Data Analyst',
                    'summary' => 'Junior analyst with strong SQL fundamentals and academic Python experience, still building professional depth.',
                    'experience' => [
                        'Junior Data Analyst | Data Start | 2025 - Present',
                        'Supports senior analysts with SQL extraction and data quality checks in a retail database.',
                        'Academic projects used Python and Pandas for exploratory data analysis.',
                        'Intern | Data Start | 2024 - 2025',
                        'Automated recurring Excel reports with spreadsheet formulas.',
                    ],
                    'skills' => 'SQL, Python, Pandas (academic), Excel, Power BI (basic)',
                    'education' => 'B.Sc. Statistics - Federal University of Goias - in progress',
                ],
                [
                    'title' => 'Marketing Analyst',
                    'summary' => 'Marketing analyst with campaign metrics experience in Excel and BI tools, without SQL or Python in daily work.',
                    'experience' => [
                        'Marketing Analyst | Mix Comunicacao | 2023 - Present',
                        'Tracks campaign performance in Excel and builds monthly reports in Looker Studio.',
                        'Collaborates with the data team to request database extracts.',
                        'Describes general familiarity with data analysis concepts but no SQL practice.',
                    ],
                    'skills' => 'Excel, Looker Studio, Marketing Metrics, Power Point',
                    'education' => 'B.Sc. Marketing - University of Vale do Sinos - 2022',
                ],
                [
                    'title' => 'Database Administrator',
                    'summary' => 'Database administrator with three years of SQL Server experience and reporting support duties.',
                    'experience' => [
                        'Database Administrator | DataGuard | 2022 - Present',
                        'Administers SQL Server instances, writes complex T-SQL queries and supports reporting requests.',
                        'Built operational dashboards in Power BI for infrastructure metrics.',
                        'Automates maintenance tasks with Python scripts.',
                    ],
                    'skills' => 'SQL Server, T-SQL, Python, Power BI, Linux, Git',
                    'education' => 'B.Sc. Computer Science - Federal University of Pernambuco - 2021',
                ],
                [
                    'title' => 'Systems Analyst',
                    'summary' => 'Systems analyst with ERP support experience, advanced Excel and SQL query knowledge for operational reporting.',
                    'experience' => [
                        'Systems Analyst | Indústria Horizonte | 2021 - Present',
                        'Supports ERP users, writes SQL queries for ad-hoc reports and documents business rules.',
                        'Trains teams on spreadsheet automation; no dedicated BI tool ownership.',
                        'Limited exposure to Python in a single training course.',
                    ],
                    'skills' => 'SQL, Excel, ERP Support, Documentation, Python (basic)',
                    'education' => 'Associate Degree in Systems Analysis - UNIP - 2020',
                ],
            ],
        ];
    }

    /** @return array<string, list<array{name: string, email: string, phone: string, city: string}>> */
    private static function identities(): array
    {
        return [
            'back-end-php-laravel-developer' => [
                ['name' => 'Lucas Ribeiro', 'email' => 'lucas.ribeiro@example.com', 'phone' => '+55 (11) 97744-1180', 'city' => 'Sao Paulo, Brazil'],
                ['name' => 'Gabriel Souza', 'email' => 'gabriel.souza@example.com', 'phone' => '+55 (11) 98650-2231', 'city' => 'Guarulhos, Brazil'],
                ['name' => 'Henrique Lopes', 'email' => 'henrique.lopes@example.com', 'phone' => '+55 (19) 99831-4407', 'city' => 'Campinas, Brazil'],
                ['name' => 'Vitor Carvalho', 'email' => 'vitor.carvalho@example.com', 'phone' => '+55 (19) 99120-8876', 'city' => 'Campinas, Brazil'],
                ['name' => 'André Martins', 'email' => 'andre.martins@example.com', 'phone' => '+55 (12) 98123-5540', 'city' => 'Sao Jose dos Campos, Brazil'],
                ['name' => 'Marcos Oliveira', 'email' => 'marcos.oliveira@example.com', 'phone' => '+55 (12) 98888-3021', 'city' => 'Sao Jose dos Campos, Brazil'],
                ['name' => 'Diego Santos', 'email' => 'diego.santos@example.com', 'phone' => '+55 (27) 99640-1188', 'city' => 'Vitoria, Brazil'],
                ['name' => 'Rafael Pereira', 'email' => 'rafael.pereira@example.com', 'phone' => '+55 (27) 99123-7754', 'city' => 'Vitoria, Brazil'],
                ['name' => 'Bruno Almeida', 'email' => 'bruno.almeida@example.com', 'phone' => '+55 (62) 98401-2298', 'city' => 'Goiania, Brazil'],
                ['name' => 'Eduardo Lima', 'email' => 'eduardo.lima@example.com', 'phone' => '+55 (62) 98310-7745', 'city' => 'Goiania, Brazil'],
                ['name' => 'Felipe Ramos', 'email' => 'felipe.ramos@example.com', 'phone' => '+55 (85) 99771-3320', 'city' => 'Fortaleza, Brazil'],
                ['name' => 'Gustavo Nunes', 'email' => 'gustavo.nunes@example.com', 'phone' => '+55 (85) 98812-6699', 'city' => 'Fortaleza, Brazil'],
            ],
            'front-end-react-developer' => [
                ['name' => 'Ana Beatriz Rocha', 'email' => 'ana.rocha@example.com', 'phone' => '+55 (48) 99640-2211', 'city' => 'Florianopolis, Brazil'],
                ['name' => 'Carolina Dias', 'email' => 'carolina.dias@example.com', 'phone' => '+55 (48) 98123-4455', 'city' => 'Florianopolis, Brazil'],
                ['name' => 'Mariana Freitas', 'email' => 'mariana.freitas@example.com', 'phone' => '+55 (51) 99770-8834', 'city' => 'Porto Alegre, Brazil'],
                ['name' => 'Juliana Prado', 'email' => 'juliana.prado@example.com', 'phone' => '+55 (51) 98450-1122', 'city' => 'Porto Alegre, Brazil'],
                ['name' => 'Ricardo Mendes', 'email' => 'ricardo.mendes@example.com', 'phone' => '+55 (61) 99210-7788', 'city' => 'Brasilia, Brazil'],
                ['name' => 'Paulo Henrique', 'email' => 'paulo.henrique@example.com', 'phone' => '+55 (61) 98660-3311', 'city' => 'Brasilia, Brazil'],
                ['name' => 'Larissa Campos', 'email' => 'larissa.campos@example.com', 'phone' => '+55 (81) 99330-2244', 'city' => 'Recife, Brazil'],
                ['name' => 'Fernanda Melo', 'email' => 'fernanda.melo@example.com', 'phone' => '+55 (81) 98770-6612', 'city' => 'Recife, Brazil'],
                ['name' => 'João Pedro Sales', 'email' => 'joao.sales@example.com', 'phone' => '+55 (92) 99440-3355', 'city' => 'Manaus, Brazil'],
                ['name' => 'Vinicius Braga', 'email' => 'vinicius.braga@example.com', 'phone' => '+55 (92) 98220-7799', 'city' => 'Manaus, Brazil'],
                ['name' => 'Sofia Rodrigues', 'email' => 'sofia.rodrigues@example.com', 'phone' => '+55 (71) 99880-1144', 'city' => 'Salvador, Brazil'],
                ['name' => 'Isabela Cardoso', 'email' => 'isabela.cardoso@example.com', 'phone' => '+55 (71) 98110-5566', 'city' => 'Salvador, Brazil'],
            ],
            'junior-data-analyst' => [
                ['name' => 'Otavio Bastos', 'email' => 'otavio.bastos@example.com', 'phone' => '+55 (11) 99150-2277', 'city' => 'Sao Paulo, Brazil'],
                ['name' => 'Renata Vieira', 'email' => 'renata.vieira@example.com', 'phone' => '+55 (11) 98330-6644', 'city' => 'Sao Paulo, Brazil'],
                ['name' => 'Camila Teixeira', 'email' => 'camila.teixeira@example.com', 'phone' => '+55 (31) 99660-8821', 'city' => 'Belo Horizonte, Brazil'],
                ['name' => 'Bruna Xavier', 'email' => 'bruna.xavier@example.com', 'phone' => '+55 (31) 98440-3399', 'city' => 'Belo Horizonte, Brazil'],
                ['name' => 'Leandro Pires', 'email' => 'leandro.pires@example.com', 'phone' => '+55 (17) 99770-4412', 'city' => 'Sao Jose do Rio Preto, Brazil'],
                ['name' => 'Thiago Rezende', 'email' => 'thiago.rezende@example.com', 'phone' => '+55 (17) 98110-9983', 'city' => 'Sao Jose do Rio Preto, Brazil'],
                ['name' => 'Amanda Correia', 'email' => 'amanda.correia@example.com', 'phone' => '+55 (41) 99220-5578', 'city' => 'Curitiba, Brazil'],
                ['name' => 'Patricia Neves', 'email' => 'patricia.neves@example.com', 'phone' => '+55 (41) 98660-1133', 'city' => 'Curitiba, Brazil'],
                ['name' => 'Rafael Duarte', 'email' => 'rafael.duarte2@example.com', 'phone' => '+55 (19) 99880-2244', 'city' => 'Campinas, Brazil'],
                ['name' => 'Julio Cesar', 'email' => 'julio.cesar@example.com', 'phone' => '+55 (19) 98770-6655', 'city' => 'Campinas, Brazil'],
                ['name' => 'Vanessa Lopes', 'email' => 'vanessa.lopes@example.com', 'phone' => '+55 (85) 99440-7711', 'city' => 'Fortaleza, Brazil'],
                ['name' => 'Sandra Gomes', 'email' => 'sandra.gomes@example.com', 'phone' => '+55 (85) 98220-4466', 'city' => 'Fortaleza, Brazil'],
            ],
        ];
    }

    private static function prefix(string $jobSlug): string
    {
        return match ($jobSlug) {
            'back-end-php-laravel-developer' => 'CV-BX',
            'front-end-react-developer' => 'CV-FX',
            'junior-data-analyst' => 'CV-DX',
            default => 'CV-XX',
        };
    }
}
