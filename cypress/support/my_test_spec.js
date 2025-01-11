// cypress/integration/my_test_spec.js
describe('My First Test', () => {
    it('Should visit the homepage and check the title', () => {
      cy.visit('https://example.com');
      cy.title().should('include', 'Example Domain');
    });
  });
  