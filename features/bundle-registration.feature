Feature: Bundle registration
    In order to install a bundle
    As a bundle installation script
    I need to register it

    Scenario: Inserting the bundle's fully qualified classname in AppKernel
        Given a Symfony2 application
        And a bundle's fully qualified classname
        When I insert it in the application's kernel
        Then the bundle should be registered
