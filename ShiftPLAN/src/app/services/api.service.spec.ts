/**
 * api.service.spe.ts
 * 
 * Tests for the ApiService
 * 
 * author: Sascha W.
 * last edit / by: 2021-05-23 / Sascha W.
 */

import { TestBed } from '@angular/core/testing';

import { ApiService } from './api.service';

describe('ApiService', () => {
  let service: ApiService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ApiService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
