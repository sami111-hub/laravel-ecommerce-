import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'app_router.dart';
import 'core/theme/app_theme.dart';
import 'providers/currency_provider.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();

  // DEBUG: Write first 5 errors to a file to find root cause
  final errorFile = File('c:/xampp82/htdocs/laravel_ecommerce_starte/store_app/error_log.txt');
  errorFile.writeAsStringSync('=== Error Log Started ===\n');
  int errorCount = 0;
  FlutterError.onError = (FlutterErrorDetails details) {
    errorCount++;
    if (errorCount <= 5) {
      final msg = '=== ERROR #$errorCount ===\n'
          '${details.exceptionAsString()}\n'
          '${details.stack?.toString() ?? "no stack"}\n'
          '=== END ERROR #$errorCount ===\n\n';
      errorFile.writeAsStringSync(msg, mode: FileMode.append);
      debugPrint('ERROR #$errorCount written to error_log.txt');
    }
  };

  SystemChrome.setPreferredOrientations([DeviceOrientation.portraitUp]);
  SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
    statusBarColor: Colors.transparent,
    statusBarIconBrightness: Brightness.dark,
  ));
  runApp(const ProviderScope(child: StoreApp()));
}

class StoreApp extends ConsumerWidget {
  const StoreApp({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    // تحميل أسعار الصرف عند بدء التطبيق
    ref.watch(currencyProvider);

    return MaterialApp.router(
      title: 'Update Aden Store',
      debugShowCheckedModeBanner: false,
      routerConfig: appRouter,
      locale: const Locale('ar'),
      theme: AppTheme.lightTheme,
      builder: (context, child) {
        return Directionality(
          textDirection: TextDirection.rtl,
          child: child!,
        );
      },
    );
  }
}
