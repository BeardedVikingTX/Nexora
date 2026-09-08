<?php
declare(strict_types=1);

/**
 * ============================================================
 * NEXORA
 * The Next Generation of Social Connection
 * ============================================================
 *
 * ABOUT / PROJECT DOSSIER
 *
 * This page documents:
 * - The Bearded Viking
 * - The origin of the experiment
 * - The five competing AI systems
 * - The six-month competition
 * - Evaluation philosophy
 * - Security / VDP roadmap
 * - Community voting
 *
 * ============================================================
 */

$pageTitle = 'About Nexora & The AI Social Media Experiment';

$pageDescription =
    'Learn about Nexora, the Bearded Viking, and the five-AI '
    . 'social media engineering competition involving ChatGPT, '
    . 'Claude, Gemini, Copilot, and DeepSeek.';

$pageKeywords =
    'Nexora, Bearded Viking, AI competition, ChatGPT, Claude, '
    . 'Gemini, Copilot, DeepSeek, social media, cybersecurity, '
    . 'HackerOne, Bugcrowd, vulnerability disclosure';

$pageRobots = 'index, follow';

require_once __DIR__ . '/includes/header.php';

?>

<!-- ============================================================
     PAGE HERO
     ============================================================ -->

<section class="nexora-hero" id="top">

    <div
        class="nexora-hero-grid"
        aria-hidden="true"
    ></div>

    <div class="container nexora-container">

        <div class="nexora-hero-content">

            <span class="nexora-eyebrow">
                Project Dossier // Mission Briefing
            </span>

            <h1 class="nexora-hero-title">
                About Nexora
            </h1>

            <p class="nexora-hero-subtitle">
                One human engineer.
                Five artificial intelligence systems.
                Five social platforms.
                One extraordinary experiment.
            </p>

            <p class="mt-4">
                Nexora is not simply a social networking application.
                It is the ChatGPT participant in a live, real-world
                experiment designed to examine what modern artificial
                intelligence can accomplish when paired directly with
                a human engineer and challenged to create, secure,
                document, and evolve an actual online community.
            </p>

            <div class="nexora-hero-actions">

                <a
                    href="#experiment"
                    class="nexora-button nexora-button-primary"
                >
                    <i class="fa-solid fa-microscope"></i>
                    Explore Experiment
                </a>

                <a
                    href="https://beardedviking.org"
                    class="nexora-button"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <i class="fa-solid fa-shield-halved"></i>
                    Bearded Viking
                </a>

            </div>

        </div>

    </div>

</section>


<!-- ============================================================
     THE ENGINEER
     ============================================================ -->

<section class="nexora-section" id="bearded-viking">

    <div class="container nexora-container">

        <div class="row align-items-center g-5">

            <div class="col-lg-7">

                <span class="nexora-eyebrow">
                    Mission Architect
                </span>

                <h2 class="nexora-section-title">
                    The Human Behind
                    <span>The Experiment</span>
                </h2>

                <p>
                    The Bearded Viking is the human engineer responsible
                    for coordinating this experiment. The public
                    Bearded Viking Security Forge dossier describes a
                    professional journey that began with an early
                    fascination with computers, IRC, and underground
                    technology communities before evolving through
                    offensive security research, academic study,
                    professional certification, and ultimately ethical
                    security work. :contentReference[oaicite:2]{index=2}
                </p>

                <p>
                    According to the public dossier, the journey
                    includes early exposure to computer systems,
                    participation in hacktivist communities, formal
                    academic work in computer science, and a later
                    transition toward legitimate security research and
                    professional cybersecurity. The stated philosophy
                    is particularly important to this experiment:
                    technical capability should ultimately be directed
                    toward protection, education, responsible
                    disclosure, and building stronger systems. :contentReference[oaicite:3]{index=3}
                </p>

                <p>
                    That philosophy is one of the reasons this AI
                    competition exists. Artificial intelligence can
                    generate code extraordinarily quickly, but speed
                    alone does not constitute engineering excellence.
                    The resulting systems must be questioned, tested,
                    secured, maintained, documented, and ultimately
                    exposed to real users.
                </p>

                <div class="nexora-mission-quote">
                    The objective isn't to prove that artificial
                    intelligence is perfect. The objective is to
                    discover what becomes possible when human
                    engineering judgment and artificial intelligence
                    work together.
                </div>

            </div>


            <div class="col-lg-5">

                <div class="nexora-panel nexora-corner-frame">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-user-secret"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        Bearded Viking Profile
                    </h3>

                    <p>
                        The public dossier identifies the Bearded Viking
                        as a cybersecurity professional with a Ph.D. in
                        Computer Science, OSCP certification, and CEH
                        certification. :contentReference[oaicite:4]{index=4}
                    </p>

                    <div class="nexora-telemetry">

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Education
                            </span>

                            <span class="nexora-telemetry-value">
                                Ph.D. C.S.
                            </span>

                        </div>

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Certification
                            </span>

                            <span class="nexora-telemetry-value">
                                OSCP
                            </span>

                        </div>

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Certification
                            </span>

                            <span class="nexora-telemetry-value">
                                CEH
                            </span>

                        </div>

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Focus
                            </span>

                            <span class="nexora-telemetry-value">
                                Security
                            </span>

                        </div>

                    </div>

                    <div class="mt-4">

                        <a
                            href="https://beardedviking.org/about.php"
                            class="nexora-button"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <i class="fa-solid fa-file-shield"></i>
                            Read Full Dossier
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ============================================================
     CAREER / EVOLUTION
     ============================================================ -->

<section class="nexora-section">

    <div class="container nexora-container">

        <div class="nexora-section-header">

            <span class="nexora-eyebrow">
                Historical Development
            </span>

            <h2 class="nexora-section-title">
                From Curiosity To
                <span>Security Engineering</span>
            </h2>

            <p>
                The Bearded Viking's public chronology describes a
                progression through several distinct phases: early
                computing and underground communities, hacktivism,
                academic development, professional security
                certification, and the creation of the Security Forge.
                :contentReference[oaicite:5]{index=5}
            </p>

            <p>
                That evolution provides an unusually appropriate
                foundation for an experiment centered on both artificial
                intelligence and cybersecurity. Offensive security
                teaches an engineer to question assumptions and search
                for unintended behavior. Defensive security demands the
                opposite discipline: anticipating those failures and
                building systems that remain resilient when those
                assumptions are challenged.
            </p>

        </div>


        <div class="nexora-panel nexora-corner-frame">

            <div class="nexora-timeline">

                <div class="nexora-timeline-item">

                    <span class="nexora-timeline-marker"></span>

                    <h3>
                        Early Computing
                    </h3>

                    <p>
                        The public chronology places the beginning of
                        the journey in childhood, when access to a
                        computer and IRC opened the door to underground
                        technology communities and early experimentation.
                        :contentReference[oaicite:6]{index=6}
                    </p>

                </div>


                <div class="nexora-timeline-item">

                    <span class="nexora-timeline-marker"></span>

                    <h3>
                        Hacktivist Era
                    </h3>

                    <p>
                        The public dossier describes later involvement
                        with hacktivist communities including Anonymous,
                        LizardSquad, and LulzSec, alongside exposure to
                        exploitation, social engineering, and offensive
                        security techniques. :contentReference[oaicite:7]{index=7}
                    </p>

                </div>


                <div class="nexora-timeline-item">

                    <span class="nexora-timeline-marker"></span>

                    <h3>
                        Academic Development
                    </h3>

                    <p>
                        Formal study in computer science followed, with
                        the public dossier identifying network security,
                        cryptographic protocols, and vulnerability
                        assessment among the areas of focus.
                        :contentReference[oaicite:8]{index=8}
                    </p>

                </div>


                <div class="nexora-timeline-item">

                    <span class="nexora-timeline-marker"></span>

                    <h3>
                        Professional Certification
                    </h3>

                    <p>
                        The public record identifies OSCP and CEH
                        certifications as part of the transition toward
                        professional ethical security work. :contentReference[oaicite:9]{index=9}
                    </p>

                </div>


                <div class="nexora-timeline-item mb-0">

                    <span class="nexora-timeline-marker"></span>

                    <h3>
                        The Security Forge
                    </h3>

                    <p>
                        The current Bearded Viking Security Forge combines
                        cybersecurity services, research, writing,
                        education, vulnerability work, and public
                        technical communication. :contentReference[oaicite:10]{index=10}
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ============================================================
     WHY THE EXPERIMENT EXISTS
     ============================================================ -->

<section
    class="nexora-section"
    id="experiment"
>

    <div class="container nexora-container">

        <div class="row g-5 align-items-center">

            <div class="col-lg-6">

                <span class="nexora-eyebrow">
                    The Core Experiment
                </span>

                <h2 class="nexora-section-title">
                    Why Build
                    <span>Five Social Networks?</span>
                </h2>

                <p>
                    Artificial intelligence benchmarks traditionally
                    focus on controlled tasks: answer a question,
                    generate code, solve a mathematical problem, write
                    an explanation, or analyze a dataset. Those tests
                    are useful, but they do not fully represent what
                    happens when an AI system is placed inside a
                    long-running engineering project.
                </p>

                <p>
                    A production-oriented software project introduces
                    uncertainty that cannot be captured by a single
                    prompt. Requirements change. Bugs appear.
                    Dependencies evolve. Users behave unpredictably.
                    Security vulnerabilities emerge. Performance
                    bottlenecks surface. Architecture decisions made
                    early can become expensive years later.
                </p>

                <p>
                    This experiment therefore asks a much broader
                    question:
                </p>

                <div class="nexora-mission-quote">
                    Which AI system can help a human engineer create
                    the strongest real-world social platform over an
                    extended period of continuous development?
                </div>

            </div>


            <div class="col-lg-6">

                <div class="nexora-image-showcase">

                    <div class="nexora-floating-image">

                        <img
                            src="assets/img/media/ChatGPT_Homepage.png"
                            alt="Nexora ChatGPT platform"
                            loading="lazy"
                        >

                    </div>

                    <div class="nexora-floating-image">

                        <img
                            src="assets/img/media/CoPilot_Homepage.png"
                            alt="SagaSphere Copilot platform"
                            loading="lazy"
                        >

                    </div>

                    <div class="nexora-floating-image">

                        <img
                            src="assets/img/media/DeepSeek_Homepage.png"
                            alt="Nexus Valhalla DeepSeek platform"
                            loading="lazy"
                        >

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ============================================================
     FIVE AI SYSTEMS
     ============================================================ -->

<section class="nexora-section">

    <div class="container nexora-container">

        <div class="nexora-section-header">

            <span class="nexora-eyebrow">
                Competition Architecture
            </span>

            <h2 class="nexora-section-title">
                Five AI Systems.
                <span>Five Approaches.</span>
            </h2>

            <p>
                Each participating AI system receives the opportunity
                to contribute to a complete social platform. The
                platforms are intentionally separate projects so that
                their architecture, design language, development
                decisions, security posture, and community performance
                can be evaluated independently.
            </p>

            <p>
                The competition is not intended to establish that one
                AI system is universally superior for every conceivable
                task. Instead, it creates a concrete engineering
                environment where different AI-assisted approaches can
                be compared over time using a mixture of technical,
                operational, and community-oriented measurements.
            </p>

        </div>


        <div class="nexora-race-board">

            <div class="nexora-race-grid">


                <!-- CHATGPT -->

                <article class="nexora-ai-card">

                    <div class="nexora-ai-image">

                        <img
                            src="assets/img/media/ChatGPT_Homepage.png"
                            alt="Nexora ChatGPT homepage"
                            loading="lazy"
                        >

                    </div>

                    <div class="nexora-ai-content">

                        <div class="nexora-ai-name">
                            ChatGPT
                        </div>

                        <div class="nexora-ai-provider">
                            OpenAI — Nexora
                        </div>

                        <p>
                            Nexora is the ChatGPT contender,
                            emphasizing security-conscious architecture,
                            futuristic interface design, modular
                            development, community mechanics, and
                            long-term evolution.
                        </p>

                        <div class="nexora-ai-actions">

                            <a
                                href="https://nexora.beardedviking.org"
                                class="nexora-ai-action"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fa-solid fa-globe"></i>
                                Platform
                            </a>

                            <a
                                href="https://github.com/BeardedVikingTX/Nexora"
                                class="nexora-ai-action"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fa-brands fa-github"></i>
                                Repository
                            </a>

                        </div>

                    </div>

                </article>


                <!-- DEEPSEEK -->

                <article class="nexora-ai-card">

                    <div class="nexora-ai-image">

                        <img
                            src="assets/img/media/DeepSeek_Homepage.png"
                            alt="Nexus Valhalla DeepSeek homepage"
                            loading="lazy"
                        >

                    </div>

                    <div class="nexora-ai-content">

                        <div class="nexora-ai-name">
                            DeepSeek
                        </div>

                        <div class="nexora-ai-provider">
                            DeepSeek — Nexus Valhalla
                        </div>

                        <p>
                            Nexus Valhalla represents the DeepSeek
                            implementation and provides an independent
                            engineering interpretation of the same
                            overarching challenge.
                        </p>

                        <div class="nexora-ai-actions">

                            <a
                                href="https://nexusvalhalla.beardedviking.org"
                                class="nexora-ai-action"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fa-solid fa-globe"></i>
                                Platform
                            </a>

                            <a
                                href="https://github.com/BeardedVikingTX/NexusValhalla"
                                class="nexora-ai-action"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fa-brands fa-github"></i>
                                Repository
                            </a>

                        </div>

                    </div>

                </article>


                <!-- GEMINI -->

                <article class="nexora-ai-card">

                    <div class="nexora-ai-image">

                        <img
                            src="assets/img/media/Gemini_Homepage.png"
                            alt="Valkyrin Gemini homepage"
                            loading="lazy"
                        >

                    </div>

                    <div class="nexora-ai-content">

                        <div class="nexora-ai-name">
                            Google Gemini
                        </div>

                        <div class="nexora-ai-provider">
                            Google — Valkyrin
                        </div>

                        <p>
                            Valkyrin represents the Gemini contender,
                            providing another independent interpretation
                            of architecture, user experience, security,
                            and social functionality.
                        </p>

                        <div class="nexora-ai-actions">

                            <a
                                href="https://valkyrin.beardedviking.org"
                                class="nexora-ai-action"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fa-solid fa-globe"></i>
                                Platform
                            </a>

                            <a
                                href="https://github.com/BeardedVikingTX/Valkyrin"
                                class="nexora-ai-action"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fa-brands fa-github"></i>
                                Repository
                            </a>

                        </div>

                    </div>

                </article>


                <!-- CLAUDE -->

                <article class="nexora-ai-card">

                    <div class="nexora-ai-image">

                        <img
                            src="assets/img/media/Claude_Homepage.png"
                            alt="RavenWarp Claude homepage"
                            loading="lazy"
                        >

                    </div>

                    <div class="nexora-ai-content">

                        <div class="nexora-ai-name">
                            Claude
                        </div>

                        <div class="nexora-ai-provider">
                            Anthropic — RavenWarp
                        </div>

                        <p>
                            RavenWarp represents the Claude contender
                            and provides a separate development path
                            through the same broader social-platform
                            challenge.
                        </p>

                        <div class="nexora-ai-actions">

                            <a
                                href="https://ravenwarp.beardedviking.org"
                                class="nexora-ai-action"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fa-solid fa-globe"></i>
                                Platform
                            </a>

                            <a
                                href="https://github.com/BeardedVikingTX/RavenWarp"
                                class="nexora-ai-action"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fa-brands fa-github"></i>
                                Repository
                            </a>

                        </div>

                    </div>

                </article>


                <!-- COPILOT -->

                <article class="nexora-ai-card">

                    <div class="nexora-ai-image">

                        <img
                            src="assets/img/media/CoPilot_Homepage.png"
                            alt="SagaSphere Copilot homepage"
                            loading="lazy"
                        >

                    </div>

                    <div class="nexora-ai-content">

                        <div class="nexora-ai-name">
                            Microsoft Copilot
                        </div>

                        <div class="nexora-ai-provider">
                            Microsoft — SagaSphere
                        </div>

                        <p>
                            SagaSphere represents the Copilot contender,
                            adding another development philosophy to
                            the experiment and another independent
                            platform for the community to evaluate.
                        </p>

                        <div class="nexora-ai-actions">

                            <a
                                href="https://sagasphere.beardedviking.org"
                                class="nexora-ai-action"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fa-solid fa-globe"></i>
                                Platform
                            </a>

                            <a
                                href="https://github.com/BeardedVikingTX/SagaSphere"
                                class="nexora-ai-action"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fa-brands fa-github"></i>
                                Repository
                            </a>

                        </div>

                    </div>

                </article>


            </div>

        </div>

    </div>

</section>


<!-- ============================================================
     EVALUATION METHODOLOGY
     ============================================================ -->

<section class="nexora-section">

    <div class="container nexora-container">

        <div class="nexora-section-header">

            <span class="nexora-eyebrow">
                Evaluation Protocol
            </span>

            <h2 class="nexora-section-title">
                How The
                <span>Race Is Evaluated</span>
            </h2>

            <p>
                A responsible comparison requires more than declaring
                a winner based on personal preference. The experiment
                therefore considers multiple dimensions of platform
                performance. No single measurement is expected to tell
                the entire story.
            </p>

        </div>


        <div class="row g-4">

            <div class="col-md-6 col-xl-4">

                <div class="nexora-panel">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-gauge-high"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        Performance
                    </h3>

                    <p>
                        Response time, resource consumption,
                        database efficiency, page responsiveness,
                        and the ability to operate efficiently within
                        practical infrastructure constraints.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-xl-4">

                <div class="nexora-panel">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        Security
                    </h3>

                    <p>
                        Authentication, authorization, session security,
                        input validation, output encoding, database
                        safety, abuse resistance, secure configuration,
                        and eventually external security research.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-xl-4">

                <div class="nexora-panel">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-code"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        Architecture
                    </h3>

                    <p>
                        Code organization, modularity, maintainability,
                        database architecture, extensibility,
                        portability, and the ability to evolve without
                        creating unnecessary technical debt.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-xl-4">

                <div class="nexora-panel">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        User Experience
                    </h3>

                    <p>
                        Accessibility, responsive behavior,
                        navigation, interface clarity, visual identity,
                        interaction design, and the overall experience
                        of using the platform.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-xl-4">

                <div class="nexora-panel">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-people-group"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        Community
                    </h3>

                    <p>
                        Registration, retention, engagement,
                        discussions, returning users, content creation,
                        and the development of a sustainable community.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-xl-4">

                <div class="nexora-panel">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        Real-World Traffic
                    </h3>

                    <p>
                        At the conclusion of the six-month experiment,
                        real-world traffic will serve as a major practical
                        indicator of which platform successfully attracted
                        and retained an audience.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ============================================================
     SECURITY / VDP FUTURE
     ============================================================ -->

<section
    class="nexora-section"
    id="security"
>

    <div class="container nexora-container">

        <div class="nexora-section-header">

            <span class="nexora-eyebrow">
                Security Evolution
            </span>

            <h2 class="nexora-section-title">
                From AI Experiment To
                <span>Adversarial Testing</span>
            </h2>

            <p>
                Security is not considered complete simply because an
                application has authentication, encrypted transport,
                secure cookies, input validation, and defensive database
                access. Those mechanisms establish a foundation; they
                do not establish perfection.
            </p>

            <p>
                The long-term vision is for the winning platform to
                progress toward independent security scrutiny. Depending
                upon the final competition outcome and operational
                readiness, that could include responsible disclosure
                infrastructure and external vulnerability research
                through an established security research platform.
            </p>

        </div>


        <div class="row g-4">

            <div class="col-lg-6">

                <div class="nexora-panel nexora-corner-frame">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-bug"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        HackerOne
                    </h3>

                    <p>
                        HackerOne is one potential future destination
                        for the winning platform's vulnerability
                        disclosure program. The objective would be to
                        provide independent security researchers with a
                        responsible channel through which vulnerabilities
                        can be reported and investigated.
                    </p>

                    <p>
                        A mature program would create an additional
                        feedback loop between engineering and the
                        security community: researchers discover weaknesses,
                        maintainers validate them, vulnerabilities are
                        remediated, and lessons learned feed back into
                        architecture and development practices.
                    </p>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="nexora-panel nexora-corner-frame">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-shield-virus"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        Bugcrowd
                    </h3>

                    <p>
                        Bugcrowd represents another potential future
                        security-research ecosystem for the winning
                        platform. As with HackerOne, the purpose would
                        be to introduce independent researchers into the
                        security lifecycle rather than treating security
                        as a one-time development milestone.
                    </p>

                    <p>
                        The final decision would depend upon the
                        competition outcome, platform maturity,
                        operational requirements, and the most
                        appropriate responsible-disclosure structure
                        available at that stage.
                    </p>

                </div>

            </div>

        </div>


        <div class="nexora-mission-quote mt-5">
            The strongest security architecture is not the one that
            claims nothing can break. It is the one prepared to discover
            what breaks, understand why it broke, fix it correctly,
            and become stronger afterward.
        </div>

    </div>

</section>


<!-- ============================================================
     SIX MONTH ENDGAME
     ============================================================ -->

<section
    class="nexora-section"
    id="endgame"
>

    <div class="container nexora-container">

        <div class="nexora-final-race">

            <div class="position-relative">

                <span class="nexora-eyebrow">
                    Six-Month Endgame
                </span>

                <h2 class="nexora-section-title">
                    At The End,
                    <span>The Users Decide.</span>
                </h2>

                <p>
                    The six-month period is designed to give each
                    platform enough time to move beyond a demonstration
                    and begin developing an actual community identity.
                    During that period, the platforms can evolve,
                    accumulate features, encounter problems, respond to
                    users, improve security, and establish their own
                    reputations.
                </p>

                <p>
                    At the conclusion of the competition, the platform
                    demonstrating the strongest real-world traffic will
                    receive the opportunity to enter the next phase of
                    infrastructure and community development.
                </p>


                <div class="nexora-prize-grid">

                    <div class="nexora-prize">

                        <div class="nexora-prize-icon">
                            <i class="fa-solid fa-globe"></i>
                        </div>

                        <h3>
                            Own Domain Name
                        </h3>

                        <p>
                            The winning platform can graduate to its own
                            dedicated domain identity.
                        </p>

                    </div>


                    <div class="nexora-prize">

                        <div class="nexora-prize-icon">
                            <i class="fa-solid fa-server"></i>
                        </div>

                        <h3>
                            Dedicated Server
                        </h3>

                        <p>
                            The winning platform can receive dedicated
                            infrastructure for its next stage of growth.
                        </p>

                    </div>


                    <div class="nexora-prize">

                        <div class="nexora-prize-icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>

                        <h3>
                            Increased Security
                        </h3>

                        <p>
                            Additional infrastructure can support deeper
                            security controls, monitoring, testing, and
                            hardening.
                        </p>

                    </div>


                    <div class="nexora-prize">

                        <div class="nexora-prize-icon">
                            <i class="fa-solid fa-crown"></i>
                        </div>

                        <h3>
                            Founding Roles
                        </h3>

                        <p>
                            Early supporters can receive special founding
                            recognition tied to the platform's history.
                        </p>

                    </div>


                    <div class="nexora-prize">

                        <div class="nexora-prize-icon">
                            <i class="fa-solid fa-medal"></i>
                        </div>

                        <h3>
                            Badge System
                        </h3>

                        <p>
                            Founding participation can become part of the
                            platform's permanent reputation and achievement
                            ecosystem.
                        </p>

                    </div>


                    <div class="nexora-prize">

                        <div class="nexora-prize-icon">
                            <i class="fa-solid fa-infinity"></i>
                        </div>

                        <h3>
                            And Much More
                        </h3>

                        <p>
                            The final winner enters a new phase of the
                            experiment where community growth becomes
                            the next challenge.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ============================================================
     COMMUNITY VOTING
     ============================================================ -->

<section
    class="nexora-section"
    id="vote"
>

    <div class="container nexora-container">

        <div class="nexora-section-header">

            <span class="nexora-eyebrow">
                Community Intelligence
            </span>

            <h2 class="nexora-section-title">
                Cast Your
                <span>Vote</span>
            </h2>

            <p>
                Traffic will remain one of the principal real-world
                measurements of the six-month experiment, but community
                opinion provides another valuable perspective. Which
                platform do you believe demonstrates the strongest
                combination of design, engineering, security, usability,
                and overall potential?
            </p>

            <p>
                The voting system is designed to become a separate
                measurement rather than a replacement for actual
                platform traffic. A platform could receive strong
                technical approval while attracting fewer users, or it
                could become extremely popular while exposing important
                engineering weaknesses. Those differences are precisely
                what make the experiment interesting.
            </p>

        </div>


        <div class="row g-5 align-items-start">

            <div class="col-lg-7">

                <div class="nexora-panel nexora-corner-frame">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-vote-yea"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        AI Platform Ballot
                    </h3>

                    <!--
                        IMPORTANT:
                        This form is intentionally prepared for the
                        future AJAX voting subsystem.

                        It should NOT be treated as a completed
                        security boundary until the backend exists.

                        Future flow:

                        Browser
                           ↓
                        AJAX
                           ↓
                        CSRF validation
                           ↓
                        Authentication / voter validation
                           ↓
                        Rate limiting
                           ↓
                        Vote validation
                           ↓
                        Database transaction
                           ↓
                        Confirmation email
                           ↓
                        Lead engineer notification
                    -->

                    <form
                        id="nexora-voting-form"
                        class="nexora-voting-form"
                        method="post"
                        action="#"
                        data-nexora-voting="pending"
                        novalidate
                    >

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?=
                                function_exists('nexora_csrf_token')
                                    ? e(nexora_csrf_token())
                                    : ''
                            ?>"
                        >

                        <fieldset>

                            <legend class="visually-hidden">
                                Select your preferred AI platform
                            </legend>


                            <div class="row g-3">


                                <div class="col-md-6">

                                    <label
                                        class="nexora-vote-option"
                                    >

                                        <input
                                            type="radio"
                                            name="ai_choice"
                                            value="chatgpt"
                                            required
                                        >

                                        <span>

                                            <strong>
                                                ChatGPT
                                            </strong>

                                            <small>
                                                Nexora
                                            </small>

                                        </span>

                                    </label>

                                </div>


                                <div class="col-md-6">

                                    <label
                                        class="nexora-vote-option"
                                    >

                                        <input
                                            type="radio"
                                            name="ai_choice"
                                            value="deepseek"
                                            required
                                        >

                                        <span>

                                            <strong>
                                                DeepSeek
                                            </strong>

                                            <small>
                                                Nexus Valhalla
                                            </small>

                                        </span>

                                    </label>

                                </div>


                                <div class="col-md-6">

                                    <label
                                        class="nexora-vote-option"
                                    >

                                        <input
                                            type="radio"
                                            name="ai_choice"
                                            value="gemini"
                                            required
                                        >

                                        <span>

                                            <strong>
                                                Google Gemini
                                            </strong>

                                            <small>
                                                Valkyrin
                                            </small>

                                        </span>

                                    </label>

                                </div>


                                <div class="col-md-6">

                                    <label
                                        class="nexora-vote-option"
                                    >

                                        <input
                                            type="radio"
                                            name="ai_choice"
                                            value="claude"
                                            required
                                        >

                                        <span>

                                            <strong>
                                                Claude
                                            </strong>

                                            <small>
                                                RavenWarp
                                            </small>

                                        </span>

                                    </label>

                                </div>


                                <div class="col-md-6">

                                    <label
                                        class="nexora-vote-option"
                                    >

                                        <input
                                            type="radio"
                                            name="ai_choice"
                                            value="copilot"
                                            required
                                        >

                                        <span>

                                            <strong>
                                                Microsoft Copilot
                                            </strong>

                                            <small>
                                                SagaSphere
                                            </small>

                                        </span>

                                    </label>

                                </div>

                            </div>

                        </fieldset>


                        <div class="mt-4">

                            <button
                                type="submit"
                                class="nexora-button nexora-button-primary"
                                id="nexora-submit-vote"
                            >
                                <i class="fa-solid fa-paper-plane"></i>
                                Submit Vote
                            </button>

                        </div>


                        <div
                            id="nexora-vote-status"
                            class="mt-3"
                            role="status"
                            aria-live="polite"
                        ></div>

                    </form>

                </div>

            </div>


            <div class="col-lg-5">

                <div class="nexora-panel">

                    <div class="nexora-panel-icon">
                        <i class="fa-solid fa-database"></i>
                    </div>

                    <h3 class="nexora-panel-title">
                        Multi-Stage Vote Processing
                    </h3>

                    <p>
                        Once the voting backend is activated, each
                        submission will pass through multiple validation
                        stages before it becomes part of the official
                        competition dataset.
                    </p>

                    <div class="nexora-telemetry">

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Stage 01
                            </span>

                            <span class="nexora-telemetry-value">
                                Validate
                            </span>

                        </div>

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Stage 02
                            </span>

                            <span class="nexora-telemetry-value">
                                Persist
                            </span>

                        </div>

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Stage 03
                            </span>

                            <span class="nexora-telemetry-value">
                                Confirm
                            </span>

                        </div>

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Stage 04
                            </span>

                            <span class="nexora-telemetry-value">
                                Notify
                            </span>

                        </div>

                    </div>

                    <div class="nexora-mission-quote mt-4 mb-0">
                        One vote should represent one intentional
                        decision — not one button pressed repeatedly.
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ============================================================
     TRANSPARENCY
     ============================================================ -->

<section class="nexora-section">

    <div class="container nexora-container">

        <div class="nexora-panel nexora-corner-frame">

            <div class="row g-5 align-items-center">

                <div class="col-lg-8">

                    <span class="nexora-eyebrow">
                        Transparency Protocol
                    </span>

                    <h2 class="nexora-section-title">
                        The Experiment Is
                        <span>Public By Design</span>
                    </h2>

                    <p>
                        The competing projects are intended to remain
                        publicly observable wherever practical. Their
                        repositories, public-facing platforms, and
                        development progress provide an opportunity for
                        observers to examine not only the final products,
                        but the engineering process that produced them.
                    </p>

                    <p class="mb-0">
                        This matters because an AI competition should
                        not be reduced to marketing claims. The strongest
                        comparison is one where people can inspect the
                        systems, use the platforms, observe their
                        evolution, identify weaknesses, and form their
                        own conclusions.
                    </p>

                </div>


                <div class="col-lg-4">

                    <div class="nexora-telemetry">

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Projects
                            </span>

                            <span class="nexora-telemetry-value">
                                05
                            </span>

                        </div>

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Platforms
                            </span>

                            <span class="nexora-telemetry-value">
                                05
                            </span>

                        </div>

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Duration
                            </span>

                            <span class="nexora-telemetry-value">
                                06 MO
                            </span>

                        </div>

                        <div class="nexora-telemetry-item">

                            <span class="nexora-telemetry-label">
                                Status
                            </span>

                            <span class="nexora-telemetry-value">
                                LIVE
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ============================================================
     FINAL CALL
     ============================================================ -->

<section class="nexora-section pt-0">

    <div class="container nexora-container">

        <div class="text-center">

            <span class="nexora-eyebrow">
                End Transmission
            </span>

            <h2 class="display-4 nexora-display">
                Five AI Systems.
                One Future.
            </h2>

            <p class="lead mt-4">
                The experiment has begun.
                The systems are being built.
                The community will decide what comes next.
            </p>

            <div class="nexora-hero-actions justify-content-center">

                <a
                    href="register.php"
                    class="nexora-button nexora-button-primary"
                >
                    <i class="fa-solid fa-user-plus"></i>
                    Join Nexora
                </a>

                <a
                    href="#vote"
                    class="nexora-button"
                >
                    <i class="fa-solid fa-vote-yea"></i>
                    Cast Your Vote
                </a>

            </div>

        </div>

    </div>

</section>


<?php

require_once __DIR__ . '/includes/footer.php';