/**
 * encryption.service.spec.ts
 * 
 * This file exists to test the functionality in 
 * the "encryption.service.ts" file.
 * 
 * author: Sascha W.
 * last edit / by: 2021-05-22 / Sascha W.
 */

import { TestBed } from '@angular/core/testing';

import { EncryptionService } from './encryption.service';

describe('EncryptionService', () => {
  let service: EncryptionService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(EncryptionService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
