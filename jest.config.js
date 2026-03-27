module.exports = {
  testEnvironment: "jsdom",
  roots: ["<rootDir>/scripts", "<rootDir>/tests"],
  testMatch: ["**/__tests__/**/*.js", "**/?(*.)+(spec|test).js"],
  collectCoverageFrom: [
    "scripts/**/*.js",
    "!scripts/**/index.js",
    "!scripts/env.js",
  ],
  coveragePathIgnorePatterns: ["/node_modules/", "/tests/"],
  moduleNameMapper: {
    "^@/(.*)$": "<rootDir>/scripts/$1",
  },
  setupFilesAfterEnv: ["<rootDir>/tests/jest.setup.js"],
  transform: {
    "^.+\\.jsx?$": "babel-jest",
  },
  collectCoverageFrom: [
    "scripts/**/*.js",
    "!scripts/env.js",
    "!scripts/vendor/**",
  ],
  coverageDirectory: "coverage",
  coverageReporters: ["text", "lcov", "html"],
  testPathIgnorePatterns: ["/node_modules/", "/vendor/"],
};
